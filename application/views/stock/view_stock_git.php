<!doctype html>
<html>
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Admin Page | View Stock DP</title>
        <!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
        <!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
        <!-- load Jquery dari CDN -->
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>all_stock/build_namacomp",    
            data: {id: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
            
</script>

<script type="text/javascript">       
    $(document).ready(function() { 
        $("#year").click(function(){
                     /*dropdown post */
            $.ajax({
            url:"<?php echo base_url(); ?>all_stock/build_namacomp",    
            data: {id: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
    });
            
</script>

<?php echo form_open($url);?>
<h2>
<?php echo form_label($page_title);?>
</h2><hr />

<div class='row'>

    <div class="col-md-3">
        <div class="form-group">
            <?php
                echo form_label(" Tahun : ");
                //$options = array(date('Y')-1=>date('Y')-1,date('Y')=>date('Y'));
                $interval=date('Y')-2010;
                $options=array();
                $options['0']='- Pilih Tahun -';
                $options['2010']='2010';
                for($i=1;$i<=$interval;$i++)
                {
                    $options[''.$i+2010]=''.$i+2010;
                }
                echo form_dropdown('year', $options, $options['0'],'class="form-control"  id="year"');
            ?>
        </div>
    </div>

    <div class="col-xs-3">
        <div class="form-group">
            <?php   
                echo form_label(" Bulan : ");     
                $bulan=array();
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
                echo form_dropdown('bulan', $bulan, '0','class="form-control"');
            ?>
        </div>
    </div>

    <?php
        //echo form_label(" SUPPLIER : ");
        $supplier=array();
        foreach($query->result() as $value)
        {
            $supplier[$value->supp]= $value->namasupp;
        }
    ?>

    <div class="col-xs-5">
            <?php 
            echo form_label(" Supplier : ");
            echo form_dropdown('supp', $supplier,'','class="form-control"  id="supp"');?>
    </div>
    

    </div>

    <div class='row'>
    <div class="col-xs-5">
        <div class="form-group">
            <?php echo br().form_submit('submit','Proses','onclick="return ValidateCompare();" class="btn btn-primary"');?>    
            <a href="<?php echo base_url()."all_stock/export_git_new/"; ?>  " class="btn btn-warning" role="button"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> export hasil proses ke dalam excel</a> 
        
        </div>
    </div>    
</div>

<br />
<?php echo form_close();?>

<?php $no = 1; ?>
<div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width="2%"><font size="2px">No</th>
                            <th width="10%"><font size="2px">Branch</th>
                            <th width="10%"><font size="2px">Sub Branch</th>
                            <th width="5%"><font size="2px">Kode Produk</th>
                            <th width="15%"><font size="2px">Nama Produk</th>
                            <th width="5%"><font size="2px">Stok Akhir</th>
                            <th width="5%"><font size="2px">GIT</th>
                            <th width="5%"><font size="2px">Tahun</th>
                            <th width="5%"><font size="2px">Bulan</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($stock as $query) : ?>
                        <tr>        
                            <td width="1"><font size="2px"><center><?php echo $no++; ?></center></td>
                            <td width="1"><font size="2px"><?php echo $query->branch_name; ?></td> 
                            <td width="1"><font size="2px"><?php echo $query->nama_comp; ?></td>               
                            <td width="1"><font size="2px"><?php echo $query->kodeprod; ?></td>
                            <td width="1"><font size="2px"><?php echo $query->namaprod; ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->stock); ?></td>
                            <td width="1"><font size="2px"><?php echo number_format($query->git); ?></td>
                            <td width="1"><font size="2px"><?php echo $query->tahun; ?></td>
                            <td width="1"><font size="2px"><?php echo $query->bulan; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </table>
        </div> 
    </div>

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                
            });
        });
        </script>

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