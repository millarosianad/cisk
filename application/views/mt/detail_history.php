<?php
$required = "required";
?>

<style type="text-css">
.half-rule { 
    margin-left: 0;
    text-align: left;
    width: 50%;
 }
</style>

<div class="pcoded-content">
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">

                        <div class="col-md-12 col-xl-12">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5><?php echo $title; ?></h5>
                                </div>

                                <div class="card-block">

                                    <div class="row">
                                        <div class="col-md-12">
                                        <a href="<?= base_url(); ?>mt/import_order" class="btn btn-dark btn-md">Kembali</a>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row mt-5">
                                        <div class="col-md-2">
                                            PO Number | Group
                                        </div>
                                        <div class="col-md-4">
                                            : <?php echo $get_detail->row()->po_number." | ".$get_detail->row()->po_group;; ?>
                                        </div>
                                        <div class="col-md-6 text-end text-right">
                                            PO Order Date : <?php echo $get_detail->row()->po_order_date; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            Delivery to name
                                        </div>
                                        <div class="col-md-4">
                                            : <?php echo $get_detail->row()->delivery_to_name; ?>
                                        </div>
                                        <div class="col-md-6 text-end text-right">
                                            <?php echo $get_detail->row()->supplier_code. " | " .$get_detail->row()->supplier_name; ?>
                                        </div>
                                    </div>
                                    <hr class="half-rule"/>
                                    <div class="row">
                                        <div class="col-md-2">
                                            Sub Branch Tujuan
                                        </div>
                                        <div class="col-md-4">
                                            <?php 
                                                if($get_detail->row()->site_code == null){
                                                    $tampil = "<font color='red'><i>belum tersedia. Mungkin mapping anda salah</i></font>";
                                                }else{
                                                    $tampil = $get_detail->row()->branch_name." | ".$get_detail->row()->nama_comp;
                                                }
                                            ?>
                                            : <?php echo $tampil; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            Toko Tujuan
                                        </div>
                                        <div class="col-md-4">
                                            <?php 
                                                if($get_detail->row()->customer_code_mpm == null){
                                                    $tampil_toko = "<font color='red'><i>belum tersedia. Mungkin mapping anda salah</i></font>";
                                                }else{
                                                    $tampil_toko = $get_detail->row()->customer_code_mpm;
                                                }
                                            ?>
                                            : <?php echo $tampil_toko; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-2">
                                            Tanggal | di proses oleh
                                        </div>
                                        <div class="col-md-4">
                                            : <?php echo $get_detail->row()->created_at." | ".$get_detail->row()->username; ?>
                                        </div>
                                    </div>


                                </div>


                                <div class="card-block">

                                                                      

<table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
    <thead>
        <tr>
            <th width="1">product code</th>
            <th width="1">product desc</th>
            <th width="1">kodeprod mpm</th>
            <th width="1">satuan mpm</th>
            <th width="1">uom</th>
            <th width="1">order</th>
            <th width="1">cust code mpm</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($get_detail->result() as $a) : ?>
        <tr>
            <td><?= $a->product_code; ?></td>
            <td><?= $a->item_descriptions; ?></td>
            <td>
                <?php 
                    if ($a->kodeprod_mpm == NULL) { ?>
                        <i><font color="red">Failed</font></i>
                    <?php } else{ ?>
                        <?= $a->kodeprod_mpm; ?>
                    <?php }
                ?>
            </td>
            <td><?php echo $a->qty1." | ".$a->qty2." | ".$a->qty3; ?></td>
            <td><?php echo $a->uom_pack_size; ?></td>
            <td><?php echo $a->order_quantity; ?></td>
            <td>
                <?php 
                    if ($a->customer_code_mpm == NULL) { ?>
                        <i><font color="red">Failed</font></i>
                    <?php } else{ ?>
                        <?= $a->customer_code_mpm; ?>
                    <?php }
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

                                </div>

                                <br>
                            </div>
                        </div>
