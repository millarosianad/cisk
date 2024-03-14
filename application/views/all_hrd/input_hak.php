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

    <?php echo form_open_multipart('all_hrd/proses_input_hak/');?>


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

        <div class="col-xs-3">Nama Karyawan (*)</div>
        <div class="col-xs-5">
            
        <?php
        if(isset($query_kary))
        {
            foreach($query_kary->result() as $value)
            {
                $grup['y']= '-- Pilih Karyawan --';
                $grup[$value->nik]= $value->nama_kary.' -- '.$value->nama_divisi;
            }
        
            echo isset($edit)?form_dropdown('nik',$grup,$nama_kary,"class=form-control"):
            form_dropdown('nik',$grup,'',"class=form-control");
        }
        
        ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Tahun (*)</div>
        <div class="col-xs-3">
            <?php
            $options = array(
                  '2'  => '-- Pilih Tahun -- ',
                  '2018'     => '2018',
                  '2017'     => '2017'
            );

            //$shirts_on_sale = array('small', 'large');
            echo form_dropdown('tahun', $options, '0','class = form-control ');

            ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Jenis Cuti (*)</div>
        <div class="col-xs-3">
            <?php
            if(isset($query_cuti))
            {
                foreach($query_cuti->result() as $value)
                {
                    $x['test']= '-- Pilih Cuti --';
                    $x[$value->id]= $value->jenis_cuti;
                }
            
                echo isset($edit)?form_dropdown('jenis_cuti',$x,$jenis_cuti,"class=form-control"):
                form_dropdown('jenis_cuti',$x,'',"class=form-control");
            }
            
            ?>

        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-3">Jumlah Cuti (*)</div>
        <div class="col-xs-3">
            <input type="text" class = 'form-control' name="hak_cuti" placeholder="">
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
            <?php echo form_submit('submit',' - Proses Hak Cuti -','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
        <div class="col-xs-2">
            <?php echo "&nbsp; &nbsp; "; ?>
             <a href="<?php echo base_url()."all_hrd/view_cuti"; ?>  " class="btn btn-default" role="button"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>  Kembali</a>
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
                            <th width="2"><font size="2px">Nik</th>
                            <th><font size="2px">Nama</th>
                            <th><font size="2px">Divisi</th>
                            <th><font size="2px">Tahun</th>
                            <th><font size="2px">Hak Cuti</th>
                            <th><font size="2px"><center>#</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($query as $div) : ?>
                        <tr>        
                            <td><center><font size="2px"><?php echo $no++; ?></font></center></td>               
                            <td><font size="2px"><?php echo $div->nik; ?></td>
                            <td><font size="2px"><?php echo $div->nama_kary; ?></td>
                            <td><font size="2px"><?php echo $div->nama_divisi; ?></td>
                            <td><font size="2px"><?php echo $div->tahun; ?></td>
                            <td><font size="2px"><?php echo $div->hak_cuti; ?></td>
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