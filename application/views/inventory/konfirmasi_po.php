<!-- <button class="btn btn-primary btn-succes btn-sm" data-toggle="modal" data-target="#tambah"><i class="fa fa-plus"></i>Tambah</button> -->
<br>
<br>
<div class="card table-card">
    <div class="card-header">
    <!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->

    <div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                <thead>
                    <tr>                
                        <th width="1%"></th>                                               
                        <th width="1"><font size="2px">Tanggal Po</th>                        
                        <th width="1"><font size="2px">Principal</th>                        
                        <th width="1"><font size="2px">Nomor Po</th>                        
                        <th width="1"><font size="2px">Company</th>                        
                        <th width="1"><font size="2px">Tipe</th>                        
                        <th width="1"><font size="2px">SubBranch</th>                        
                        <th width="1"><font size="2px">TotalSKU</th>                        
                    </tr>
                </thead>
                <tbody>
                <?php  foreach($get_po as $a) : ?>
                    <tr>        
                        <td>
                        <?php 
                            if ($a->x == $a->y) {
                                echo anchor(base_url()."inventory/konfirmasi_po_detail/".$a->supp.'/'.$a->id,"&nbsp;",[
                                    'target' => '_blank',
                                    'class'=>'fa fa-check-circle fa-lg',
                                    'style'=>"color:green"
                                ]);
                            }elseif($a->y == null){
                                echo anchor(base_url()."inventory/konfirmasi_po_detail/".$a->supp.'/'.$a->id,"&nbsp;",[
                                    'target' => '_blank',
                                    'class'=>'fa fa-exclamation-circle fa-lg',
                                    'style'=>"color:red"
                                ]);
                            }else{
                                echo anchor(base_url()."inventory/konfirmasi_po_detail/".$a->supp.'/'.$a->id,"&nbsp;",[
                                    'target' => '_blank',
                                    'class'=>'fa fa-exclamation-circle fa-lg',
                                    'style'=>"color:orange"
                                ]);
                            }
                        ?>
                        </td>       
                        <!-- <td width="1px"><font size="3px">
                            <?php
                            echo anchor(base_url()."inventory/konfirmasi_po_detail/".$a->supp.'/'.$a->id,"click here",[
                                    'target' => '_blank',
                                    'class'=>'fa fa-check-circle fa-lg',
                                    'style'=>"color:red"
                                ]);
                            ?>
                        </td>   -->
                        <td><font size="2px"><?php echo $a->tglpo; ?></td>
                        <td><font size="2px"><?php echo $a->namasupp; ?></td>
                        <td><font size="2px"><?php 
                        if ($a->nopo == null) {
                            echo "<i>".anchor(base_url()."transaction/download_pdf/".$a->id,'belum tersedia',['target' => '_blank']);
                        }else{
                            echo anchor(base_url()."transaction/download_pdf/".$a->id,$a->nopo,['target' => '_blank']);
                        } ?>
                        </td>
                        <td><font size="2px"><?php echo $a->company; ?></td>
                        <td><font size="2px"><?php echo $a->tipe; ?></td>
                        <td><font size="2px"><?php echo $a->nama_comp; ?></td>
                        <td><font size="2px"><?php echo $a->x; ?></td>
                        
                    </tr>                            
                
                <?php endforeach; ?>
                
                </tbody>
                
            </table>
        </div>
        </div>
        </div>
</div>

<?php 
// $this->load->view('master_product/modal_addproduct'); 
// $this->load->view('inventory/modal_edit_konfirmasi_po');
// $this->load->view('master_product/modal_addharga'); 
?>
