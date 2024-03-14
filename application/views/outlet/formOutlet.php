<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()."assets/js/checkboxgroup.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#subbranch").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>outlet/buildSalesName",    
            data: {id_subbranch: $(this).val()},
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
</pre>
<hr>




<div class="container">    
    <div class="row">    
        <div class="col-sm-4">
                <label for="">Start Date</label>
        </div>
        <div class="col-sm-2">
                <label for="">End Date</label>
        </div>
    </div>

    <div class="row">
    <div class="col-sm-2">
            <?php 
            $interval=date('Y')-2010;
            $options=array();
            $options['0']='- Tahun -';
            $options['2010']='2010';
            for($i=1;$i<=$interval;$i++)
            {
                $options[''.$i+2010]=''.$i+2010;
            }
            echo form_dropdown('tahun', $options, $options['0'],'class="form-control"  id="year"');          
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            $bulan['0']='- Bulan -';
            $bulan['1']='Januari';
            $bulan['2']='Februari';
            $bulan['3']='Maret';
            $bulan['4']='April';
            $bulan['5']='Mei';
            $bulan['6']='Juni';
            $bulan['7']='Juli';
            $bulan['8']='Agustus';
            $bulan['9']='September';
            $bulan['10']='Oktober';
            $bulan['11']='November';
            $bulan['12']='Desember';
            echo form_dropdown('bulan', $bulan, $options['0'],'class="form-control form-control-lg"');      
            ?>
        </div>

        <div class="col-sm-2">
            <?php 
            $interval=date('Y')-2010;
            $options=array();
            $options['0']='- Tahun -';
            $options['2010']='2010';
            for($i=1;$i<=$interval;$i++)
            {
                $options[''.$i+2010]=''.$i+2010;
            }
            echo form_dropdown('endTahun', $options, $options['0'],'class="form-control"  id="year"');          
            ?>
        </div>
        <div class="col-sm-2">
            <?php
            $bulan['0']='- Bulan -';
            $bulan['1']='Januari';
            $bulan['2']='Februari';
            $bulan['3']='Maret';
            $bulan['4']='April';
            $bulan['5']='Mei';
            $bulan['6']='Juni';
            $bulan['7']='Juli';
            $bulan['8']='Agustus';
            $bulan['9']='September';
            $bulan['10']='Oktober';
            $bulan['11']='November';
            $bulan['12']='Desember';
            echo form_dropdown('endBulan', $bulan, $options['0'],'class="form-control form-control-lg"');      
            ?>
        </div>
    </div>    
    
<br>
    <div class="row">
        <div class="col-sm-2">
            <label for="">Sub Branch</label>
        </div>
        <div class="col-sm-2">
            <label for="">Unit / Value</label>
        </div>
        <div class="col-sm-3">
            <label for="">Salesman</label>
        </div>
        <div class="col-sm-2">
            <label for="">Breakdown By</label>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2">
            <?php        
                $options=array();
                $options['0']='- Pilih Sub Branch -';
                echo form_dropdown('nocab', $options, 'ALL','class="form-control" id="subbranch"');
            ?>
        </div>
        <div class="col-sm-2">
            <?php        
                $options=array();
                $options['0']='UNIT';
                $options['1']='VALUE';
                echo form_dropdown('uv', $options, 'UNIT','class="form-control"');
            ?>
        </div>
        <div class="col-sm-3">
            <?php        
                $options=array();
                $options['0']='ALL';
                $options['1']='PILIH DP';
                echo form_dropdown('sm', $options, 'ALL','class="form-control" id="salesman"');
            ?>
        
        </div>

        <div class="col-sm-2">
            <?php        
                $options=array();
                $options['0']='-';
                $options['1']='Kode Produk';
                echo form_dropdown('bd', $options, 'ALL','class="form-control"');
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
        <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>  
        </div>
      
    </div>
</div>









<div class='row'>
   


    <div class="col-md-3">
        <div class="form-group">
            
        </div>
    </div> 

    <!--
    <div class="col-md-2">
        <div class="form-group">
            <?php
                //echo form_label("DP : ");
                //foreach($query2->result() as $value)
                {
                    $dd[$value->naper]= $value->nama_comp;                                         
                }
                //echo form_dropdown('nocab',$dd,'','class="form-control" id="dp"');
            ?>
        </div>
    </div>
    -->
   

    <div class="col-md-2">
        <div class="form-group">
           
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