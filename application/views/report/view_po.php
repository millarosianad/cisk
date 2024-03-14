<!doctype html>
<html>
    <head>
        <title>codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <link rel="stylesheet" href="<?php echo base_url('assets/select2/css/select2.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
    
    <?php //echo br(3); ?>
    <?php echo form_open('all_report/data_po_hasil/');?>
    <?php //echo form_label($page_title);?>
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
            <?php echo br(3); ?>
            <h3>Laporan PO</h3><hr />
        </div>

        <div class="col-xs-2">
            Silahkan pilih tahun :
        </div>

        <div class="col-xs-5">
            <?php echo form_dropdown('tahun', $year,date('Y'),"class='form-control'");?>
        </div>
        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            Silahkan pilih bulan :
        </div>

        <div class="col-xs-5">
            
        <?php
            $options = array(
                  '01'  => 'Januari',
                  '02'    => 'Februari',
                  '03'   => 'Maret',
                  '04' => 'April',
                  '05'  => 'Mei',
                  '06'    => 'Juni',
                  '07'   => 'Juli',
                  '08' => 'Agustus',
                  '09'  => 'September',
                  '10'    => 'Oktober',
                  '11'   => 'November',
                  '12' => 'Desember',
                );

            //$shirts_on_sale = array('small', 'large');

            echo form_dropdown('bulan', $options, 'large', "class='form-control'");

        ?>

        </div>

        <div class="col-xs-11">&nbsp;</div>

        

        <div class="col-xs-2">

        
            <?php echo form_submit('submit','Proses','class="btn btn-primary"');?>
            <?php echo form_close();?>
        </div>
    </div>
    <?php 
        echo "<pre>";
        echo "tahun yang dipilih : ".$tahun;
        echo " || ";
        echo "bulan yang dipilih : ".$bulan;
        
        echo "</pre>";
    ?>
    <?php 

        if ($bulan == '1') {
            $bulanx = "Januari";
        }
        elseif ($bulan == '2') {
            $bulanx = "Februari";
        }
        elseif ($bulan == '3') {
            $bulanx = "Maret";
        }
        elseif ($bulan == '4') {
            $bulanx = "April";
        }
        elseif ($bulan == '5') {
            $bulanx = "Mei";
        }
        elseif ($bulan == '6') {
            $bulanx = "Juni";
        }
        elseif ($bulan == '7') {
            $bulanx = "Juli";
        }
        elseif ($bulan == '8') {
            $bulanx = "Agustus";
        }
        elseif ($bulan == '9') {
            $bulanx = "September";
        }
        elseif ($bulan == '8') {
            $bulanx = "Agustus";
        }elseif ($bulan == '9') {
            $bulanx = "September";
        }elseif ($bulan == '10') {
            $bulanx = "Oktober";
        }elseif ($bulan == '11') {
            $bulanx = "November";
        }elseif ($bulan == '12') {
            $bulanx = "Desember";
        }

        echo "<pre>";
        echo "keterangan : <br>";
        echo "1. Total PO : Total Seluruh PO yang masuk di bulan<i> $bulanx</i> <b>(Approve + Pending)</b> <br>";
        echo "2. Approve : Semua PO yang masuk di bulan<i> $bulanx </i><b>dan sudah di approve/disetujui</b>, Namun mungkin tidak semua PO di jalankan<br>";
        echo "3. Pending : Semua PO yang masuk di bulan<i> $bulanx</i><b> dan status nya pending/belum disetujui </b><br>";
         echo "3. Actual PO : Semua PO yang dijalankan</b> pada bulan<i> $bulanx</i>, Namun mungkin ada PO yang masuk di bulan sebelumnya<br>";
        echo "</pre>";
    ?>
    <hr>
    <?php $no = 1 ; ?> 
     <div class="row">        
        <div class="col-xs-19">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th>Principal</th>
                            <th>Total_PO</th>
                            <th>Total_PO(Value)</th>                            
                            <th>Approve</th>
                            <th>Approve_(Value)</th>
                            <th>Pending</th>
                            <th>Pending_(Value)</th> 
                            <th>Actual_PO</th>
                            <th>Actual_PO(Value)</th>                                                    
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($reports as $report) : ?>
                        <tr>        
                            <td><?php echo $report->namasupp; ?></td>
                            <td><center>                            
                                <?php 
                                    //echo $report->total_po; 
                                    echo anchor('all_report/list_po_custom/all/' . $report->supp . '/' . $tahun . '/' . $bulan, $report->total_po, 'target = _blank');
                                ?>
                            </center>
                            </td>
                            <td><?php echo "Rp." .$report->all_value; ?></td>
                              
                            <td>
                                <center>                            
                                <?php 
                                    //echo $report->total_po; 
                                    echo anchor('all_report/list_po_custom/approve/' . $report->supp . '/' . $tahun . '/' . $bulan, $report->approve, 'target = _blank');
                                ?>
                                </center>
                            </td>
                            <td><?php echo "Rp." .$report->approve_value; ?></td>
                            <td>
                                <center>
                            
                                <?php 
                                    //echo $report->total_po; 
                                    echo anchor('all_report/list_po_custom/pending/' . $report->supp . '/' . $tahun . '/' . $bulan, $report->pending, 'target = _blank');
                                ?>
                                </center>
                            </td>
                            <td><?php echo "Rp." .$report->pending_value; ?></td>
                            <td><center>                            
                                <?php 
                                    //echo "sedang diperbaiki"//echo $report->total_po; 
                                    echo anchor('all_report/list_po_custom/actual/' . $report->supp . '/' . $tahun . '/' . $bulan, $report->actual_po, 'target = _blank');
                                ?>
                            </center>
                            </td>
                            <td><?php 
                                //echo "sedang diperbaiki"
                                echo "Rp." .$report->actual_value; ?></td>                          
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    </table>
        </div> 
    </div>
        
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