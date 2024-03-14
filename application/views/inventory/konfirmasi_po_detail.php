<?php echo form_open($url); ?>
<div class="col-xs-16">
    <a href="<?php echo base_url()."inventory/konfirmasi_po"; ?>  " class="btn btn-primary btn-md" role="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> kembali</a> 
    <hr>

</div>
<div class="card table-card">
    <div class="card-header">
    <!-- <p id="loadingImage" style="font-size: 60px; display: none">Loading ...</p> -->

    <div class="card-block">
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">    
                <thead>
                    <tr>                
                        <th width="1%"><center><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all" onclick="click_all_request()" ></th>                  
                        <th width="1%"><font size="2px">tanggal terima</th>                        
                        <th width="1%"><font size="2px">kodeprod</th>                        
                        <th width="1%"><font size="2px">namaprod</th>                        
                        <!-- <th width="1%"><font size="2px">qtyPo</th>                         -->
                        <!-- <th width="1%"><font size="2px">qtyPo(karton)</th>                         -->
                        <th width="1%"><font size="2px">noDo</th>                        
                        <th width="1%"><font size="2px">tglDo</th>                        
                        <th width="1%"><font size="2px">qtyPemenuhan</th>                        
                        <!-- <th width="1%"><font size="2px">qtyPemenuhanKarton</th>                         -->
                    </tr>
                </thead>
                <tbody>
                <?php  foreach($get_do as $a) : ?>
                    <tr>   
                        <td>
                            <center>
                            <input type="checkbox" id="<?php echo $a->kodeprod; ?>" name="options[]" class = "<?php echo $a->kodeprod; ?>" value="<?php echo $a->kodeprod; ?>">
                            </center>
                        </td>  
                        <td><font size="2px"><?php
                            if ($a->status_terima == 1) {
                                echo $a->tanggal_terima;
                            }else{
                                echo "belum diterima";
                            }
                        ?></td>
                        <td><font size="2px"><?php echo $a->kodeprod; ?></td>
                        <td><font size="1px"><?php echo $a->namaprod; ?></td>
                        <!-- <td><font size="2px"><?php echo $a->banyak; ?></td> -->
                        <!-- <td><font size="2px"><?php echo $a->banyak_karton; ?></td> -->
                        <td><font size="2px"><?php echo $a->nodo; ?></td>
                        <td><font size="2px"><?php echo $a->tgldo; ?></td>
                        <td><font size="2px"><?php echo $a->qty; ?></td>
                        <!-- <td><font size="2px"><?php echo $a->qty_karton; ?></td> -->
                        
                    </tr>                            
                
                <?php endforeach; ?>
                
                </tbody>
                
            </table>
            </div>
        <div class="col-xs-11">&nbsp; </div>
        </div>
</div>

<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tanggal terima (*)</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <!-- <textarea name="note" cols="30" rows="3" class = "form-control" required></textarea> -->
                    <input class="form-control" type="date" name="tanggal_terima" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">            
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php 
                        $uri_id = $this->uri->segment('4');
                        $uri_supp = $this->uri->segment('3');
                        // echo $uri_id;
                    ?>
                    <input type="hidden" value=<?php echo $uri_id; ?> name = "id_po">
                    <input type="hidden" value=<?php echo $uri_supp; ?> name = "supp">

                    <button type="submit" class="btn btn-primary" name="btn_terima" value="1" onclick="return confirm('Apakah yakin anda sudah terima do tsb?');">Sudah Terima</button>
                    <button type="submit" class="btn btn-warning" name="btn_cancel" value="1" onclick="return confirm('Apakah yakin belum menerima do tsb?');">Belum Terima</button>
                                        
                </div>
            </div>
        </div>
    </div>

<?php 
// $this->load->view('master_product/modal_addproduct'); 
// $this->load->view('inventory/modal_edit_konfirmasi_po');
// $this->load->view('master_product/modal_addharga'); 
?>
