<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>List Order</title>
        <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
        <!-- Load Datatables dan Bootstrap dari CDN -->
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
        <script type="text/javascript" src="<?php echo base_url()."assets/js/select.js"?>"></script>
        <script type="text/javascript">       
        $(document).ready(function() { 
        $("#supp").click(function(){
            $.ajax({
            url:"<?php echo base_url(); ?>omzet/buildgroup",    
            data: {kode_supp: $(this).val()},
            type: "POST",
            success: function(data){
                $("#subbranch").html(data);
                }
            });
        });
        });            
        </script>
    </head>
    <body>
    <?php 
        foreach($header as $x){
            $namasupp = $x->namasupp;
            $branch_name = $x->branch_name;
            $nama_comp = $x->nama_comp;
            $kode = $x->kode;
            $supp = $x->supp;
            $userid = $x->userid;
            $company = $x->company;
            $alamat_kirim = $x->alamat;
            $nopo = $x->nopo;
            $tglpesan = $x->tglpesan;
            $tglpo = $x->tglpo;
            $tipe = $x->tipe;
            $note = $x->note;
            $id = $x->id;
            $tahun = $x->tahun;
            $bulan = $x->bulan;
            $status = $x->status;
            $status_approval = $x->status_approval;
            $alasan_approval = $x->alasan_approval;
            $po_ref = $x->po_ref;            
            $lock = $x->lock;            
        }
    ?>
    <div class="row">        
        <div class="col-xs-16">            
            <h3><?php echo br(1).' '.$page_title; ?></h3><hr />
        </div>        
        <div class="col-xs-2">
            Supplier
        </div>
        <div class="col-xs-9">
            <?php echo $namasupp; ?>
        </div>
        <div class="col-xs-2">
            Branch Name / Company
        </div>
        <div class="col-xs-9">
            <?php echo $branch_name." - ".$nama_comp." / ".$company." (".$kode.") - ".$userid; ?>
        </div>
        <div class="col-xs-11">&nbsp;</div>
        <div class="col-xs-2">
            Nomor ID Order
        </div>
        <div class="col-xs-9">
            <?php echo $id; ?>
        </div>
        <div class="col-xs-2">
            Tanggal Pesan
        </div>
        <div class="col-xs-9">
            <?php echo $tglpesan; ?>
        </div>
        <div class="col-xs-2">
            Nomor PO
        </div>
        <div class="col-xs-9">
        <?php 
            if ($nopo == '') {
                echo "<font color='red'><i>belum ada</i></font>";
            }else{
                echo $nopo;
            }                 
        ?>
        </div>       
        <div class="col-xs-2">
            Tanggal PO
        </div>
        <div class="col-xs-9">
        <?php 
            if ($tglpo == '') {
                echo "<font color='red'><i>belum ada</i></font>";
            }else{
                echo $tglpo;
            }  
        ?>
        </div>
        <div class="col-xs-2">
            Tipe
        </div>
        <div class="col-xs-9">
            <?php 
                if($tipe == 'S'){
                    echo "SPK";
                }elseif($tipe == 'A'){
                    echo "Alokasi";
                }elseif($tipe == 'R'){
                    echo "Replineshment";
                }                 
            ?>
        </div>

        <div class="col-xs-2">
            Status Order
        </div>

        <div class="col-xs-9">
            <?php 
                if($status == '1'){
                    echo "Proses (admin)";
                }elseif($status == '2'){
                    echo "Proses (finance)";
                }else{
                    echo "Pending";
                }                 
            ?>
        </div>

        <div class="col-xs-2">
            Status Approval
        </div>

        <div class="col-xs-9">
            <?php 
                if($status_approval == '1'){
                    echo "Approved";
                }else{
                    echo " Tidak ada ";
                }                 
            ?>
        </div>

        <div class="col-xs-2">
            Alasan Approval
        </div>

        <div class="col-xs-9">
            <?php 
                if($alasan_approval == 'null' || $alasan_approval == ''){
                    echo "Tidak ada";
                }else{
                    echo $alasan_approval; 
                }            
            ?>
        </div>

        <div class="col-xs-11"><hr></div>
        <?php echo form_open($url_po);?> 
        <div class="col-xs-2">
            Nomor PO
        </div>

        <div class="col-xs-4">
            <?php 
                if ($bulan == '1') {
                    $bulan_po = 'I';
                }elseif ($bulan == '2') {
                    $bulan_po = 'II';
                }elseif ($bulan == '3') {
                    $bulan_po = 'III';
                }elseif ($bulan == '4') {
                    $bulan_po = 'IV';
                }elseif ($bulan == '5') {
                    $bulan_po = 'V';
                }elseif ($bulan == '6') {
                    $bulan_po = 'VI';
                }elseif ($bulan == '7') {
                    $bulan_po = 'VII';
                }elseif ($bulan == '8') {
                    $bulan_po = 'VIII';
                }elseif ($bulan == '9') {
                    $bulan_po = 'IX';
                }elseif ($bulan == '10') {
                    $bulan_po = 'X';
                }elseif ($bulan == '11') {
                    $bulan_po = 'XI';
                }elseif ($bulan == '12') {
                    $bulan_po = 'XII';
                }
                $isi_nopo = $this->uri->segment('5');
                
                if ($isi_nopo != '') {
                    $isi_nopo_x = $isi_nopo."/MPM/".$bulan_po."/".$tahun; 
                }else{
                    if ($nopo != '') {
                        $isi_nopo_x = $nopo;
                    }else{
                        $isi_nopo_x = '';
                    }
                    
                }
            ?>
            <input type="text" class="form-control" name="nopo" placeholder = "nomor po" value="<?php echo $isi_nopo_x ?>" required readonly>
        </div>

        <!-- <div class="col-xs-4">
            <a href="<?php echo base_url()."all_po/generate_new/".$id."/".$supp ?> " class="btn btn-default" role="button">Generate No PO</a> 
        </div> -->

        <div class="col-xs-11"></div>

        <div class="col-xs-2">
            Alamat Kirim
        </div>

        <div class="col-xs-4">
            <textarea class="form-control" name="alamat_kirim" required readonly><?php echo $alamat_kirim ?></textarea>
        </div>

        <div class="col-xs-11"></div>

        <div class="col-xs-2">
            Note
        </div>

        <div class="col-xs-4">
            <textarea class="form-control" name="note"><?php echo $note ?></textarea>
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2">
            PO Ref
        </div>

        <div class="col-xs-4">
        <input type="text" class="form-control" name="po_ref" placeholder = "po ref" value="<?php echo $po_ref ?>">
        </div>

        <div class="col-xs-11">&nbsp;</div>

        <div class="col-xs-2"></div>

        <!-- <div class="col-xs-4">
            <input type="hidden" class="form-control" name="id_po" value="<?php echo $id; ?>">
            <?php echo form_submit('submit','Submit PO','class="btn btn-primary"')?>
            <?php echo form_close(); ?>
        </div> -->

        <!-- <div class="col-xs-11"><hr></div>
        
        <?php echo form_open($url);?>  
        <div class="col-xs-5">
        <?php 
            $x=array();
            foreach($product->result() as $value)
            {
                $x[$value->kodeprod]= $value->kodeprod.' - '.$value->namaprod;
            }

            echo form_dropdown('kodeprod', $x,'','class="form-control"');
        ?>
        </div>

        <div class="col-xs-2">
            <input type="text" class="form-control" name="jumlah" placeholder = "value in karton" required>
            <input type="hidden" class="form-control" name="id_po" value="<?php echo $id; ?>">
            <input type="hidden" class="form-control" name="userid" value="<?php echo $userid; ?>">
        </div>

        <div class="col-xs-2">
        <?php 
            if ($lock == '1') { ?>
                <button type="button" class="btn btn-primary" disabled>Locked</button>
            <?php 
            }else{ ?>
                <?php echo form_submit('submit','Tambah Produk','class="btn btn-primary"')?>     
                <?php echo form_close(); ?>

            <?php
            }
        ?>
        </div> -->


        <!-- <div class="col-xs-11"><hr></div>

        <div class="col-xs-11">
        <?php 
        if ($lock == '1' && $nopo != null) { ?>

            <a href="<?php echo base_url()."all_po/proses_doi/".$id."/".$tahun."/".$bulan."/".$tglpesan."/".$kode."/".$supp ?> " class="btn btn-warning" role="button" disabled>Analisa DOI</a> 
            <a href="<?php echo base_url()."all_po/proses_po_to_finance/".$id."/".$supp ?> " class="btn btn-success" role="button" disabled>Kirim ke Finance</a> 
            <a href="<?php echo base_url()."all_po/unlock_finance/".$id."/".$supp ?> " class="btn btn-danger" role="button" disabled>Unlock Status Finance Approval</a>  -->
            <!-- <a href="<?php echo base_url()."all_po/proses_po_to_finance/".$id."/".$supp ?> " class="btn btn-info" role="button">Email Permintaan Approval</a> -->

        <!-- <?php
        }else{ ?>

            <a href="<?php echo base_url()."all_po/proses_doi/".$id."/".$tahun."/".$bulan."/".$tglpesan."/".$kode."/".$supp ?> " class="btn btn-warning" role="button">Analisa DOI</a> 
            <a href="<?php echo base_url()."all_po/proses_po_to_finance/".$id."/".$supp ?> " class="btn btn-success" role="button">Kirim ke Finance</a> 
            <a href="<?php echo base_url()."all_po/unlock_finance/".$id."/".$supp ?> " class="btn btn-danger" role="button">Unlock Status Finance Approval</a>  -->
            <!-- <a href="<?php echo base_url()."all_po/proses_po_to_finance/".$id."/".$supp ?> " class="btn btn-info" role="button">Email Permintaan Approval</a> -->

        <!-- <?php
        }
        ?>
                  
        </div>

        <div class="col-xs-11"><hr></div>
        <?php echo form_open($url_approval);?>
        <div class="col-xs-5">
            <input type="text" name="alasan_approval" class="form-control" placeholder="masukkan alasan approval disini .. " required> 
        </div> -->

        <!-- <div class="col-xs-2"> -->
            <!-- <input type="text" name="key_approval" class="form-control" placeholder="key approval"> -->
        <!-- </div> -->

        <!-- <div class="col-xs-2">
            <input type="hidden" class="form-control" name="supp" value="<?php echo $supp; ?>">
            <input type="hidden" class="form-control" name="id_po" value="<?php echo $id; ?>">
            <?php
            if ($lock == '1') { 
                echo form_submit('submit','Submit Approval dan Kirim ke Finance','class="btn btn-primary" disabled');
            }else{
                echo form_submit('submit','Submit Approval dan Kirim ke Finance','class="btn btn-primary"');
            }
            ?>
        </div> -->

    </div>
    <hr>
    <div class="row"> 
        <div class="col-xs-12">
            <div class="col-xs-12">
            <table id="myTable" class="table table-striped table-bordered table-hover">     
                    <thead>
                        <tr>                
                            <th width='1'><center>Kodeprod</font></th>
                            <th width='1'><center>Kodeprc</th>
                            <th><center>Namaprod</th>
                            <th><center>unit</th>
                            <th><center>Stock Akhir</th>
                            <th><center>Git</th>
                            <th><center>Rata - Rata</th>
                            <th><center>DOI</th>
                            <th><center>Deleted</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                        <?php foreach($detail as $x) : ?>
                        <tr>        
                            <td><?php echo $x->kodeprod; ?></font></center></td>   
                            <td><?php echo $x->kode_prc; ?></td>            
                            <td><?php echo $x->namaprod; ?></td>
                            <td><?php echo $x->banyak; ?></td>
                            <td><?php echo $x->stock_akhir; ?></td>
                            <td><?php echo $x->git; ?></td>
                            <td><center><?php echo $x->rata; ?></td>
                            <td><?php echo $x->doi; ?></td>
                            <td><center>
                                <!-- <?php 
                                // echo "lock : ".$lock;
                                if ($lock === '1') { ?>
                                    <button type="button" class="btn btn-primary" disabled>Locked</button>
                                <?php
                                }else{ 
                                    echo anchor('all_po/delete_product/' . $id . '/' . $x->id_kodeprod. '/' . $x->supp, ' ',array('class' => 'glyphicon glyphicon-remove','onclick'=>'return confirm(\'Are you sure?\')'));
                                }
                            ?> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    </tbody>
                    </table>
                    </div>
                    <div class="col-xs-11">&nbsp; </div>
        </div>
    </div>
    

     <script>
        $(document).ready(function(){
            $('#myTable').DataTable( {
                "ordering": true,
                "pageLength": 50,
                "lengthMenu": [[10, 25, 50, 100, 150, -1], [10, 25, 50, 100, 150, "All"]]
            });
        });
        </script>
    </body>
</html>