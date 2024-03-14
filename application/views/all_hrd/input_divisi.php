<!doctype html>
<html>
    <head>
        
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <link rel="stylesheet" href="<?php echo base_url('assets/select2/css/select2.css') ?>"/>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <style>
            body{
                padding: 15px;
            }
        </style>

        <!-- Load SCRIPT.JS which will create datepicker for input field  -->
        <script src="<?php echo base_url() ?>assets/js/script.js"></script>

        <script type="text/javascript">
        function onlyNumbers(event)
        {
            var e = event || evt; // for trans-browser compatibility
            var charCode = e.which || e.keyCode;

            if ((charCode < 48 || charCode > 57) && (charCode < 37 || charCode>40) && (charCode < 8 || charCode >8) && (charCode < 46 || charCode > 46) )
                    return false;
                 return true;

        }
        </script>
    </head>
    <body>

    <?php echo form_open_multipart('all_hrd/proses_input_divisi/');?>

    <?php 
        //echo form_label(" Year : ");
        //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
        $interval=date('Y')-2010;
        $year=array();
        $year['2010']='2010';
        for($i=1;$i<=$interval;$i++)
        {
            $year[''.$i+2010]=''.$i+2010;
        }
        //echo br(5);
        //echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");
    ?>            
    <div class="row">
            
        <div class="col-xs-16">
            <?php echo br(4); ?>
            <h3><?php echo  $page_title; ?></h3><hr />
        </div>

        <div>
        <font color="red">
            <?php 
                echo validation_errors(); 
                echo br(1);
            ?>
        </font>
        </div> 

        <div class="col-xs-3">Kode Divisi (*)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="kode_divisi" placeholder="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Nama Divisi (*)</div>
        <div class="col-xs-5">
            <input type="text" class = 'form-control' name="nama_divisi" placeholder="">
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <!-- batas -->
        <div class="col-xs-11"><hr><br></div>
        <!-- batas -->
        
    </div>

    <div class="row">
        <div class="col-xs-2">
            
        </div>
        <div class="col-xs-2">
            <?php echo form_submit('submit',' - Proses Tambah Divisi -','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
        <div class="col-xs-2">
            <?php echo "&nbsp; &nbsp; "; ?>
             <a href="<?php echo base_url()."all_hrd/"; ?>  " class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>  Kembali</a>
        </div>  
    </div>

    <div class="row">
        <!-- batas -->
        <div class="col-xs-11"><hr><br></div>
        <!-- batas -->
    </div>
    <?php $no = 1; ?>

    <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="1"><font size="2px">No</font></th>
                            <th width="2"><font size="2px">Kode Divisi</th>
                            <th><font size="2px">Nama Divisi</th>
                            <th><font size="2px"><center>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($query as $div) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $div->kode_divisi; ?></td>
                            <td><font size="2px"><?php echo $div->nama_divisi; ?></td>
                            <td>
                                <center>
                                <?php
                                    echo anchor('all_hrd/detail_complain/' . $div->id, 'edit',"class='btn btn-primary btn-sm'");
                                ?>
                                <?php 
                                    echo anchor('all_hrd/delete_complain/' . $div->id, 'delete',
                                        array('class' => 'btn btn-danger btn-sm',
                                              'onclick'=>'return confirm(\'Are you sure?\')'));
                                ?>
                                </center>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
        </div>
    </div>
    

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": false,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>


    <br><br><br>
    
    <div class="row">
        <!--jquery dan select2-->
        <script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js') ?>"></script>
        <script src="<?php echo base_url('assets/select2/js/select2.min.js') ?>"></script>
        <script>
            $(document).ready(function () {
                $(".select2").select2({
                    placeholder: "Please Select"
                });
            });
        </script>
    </body>
</html>