<script>
    function getEditIDProduct(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_product/get_productsid') ?>",
            data: {
                id: params
            },
            dataType: "json",
            beforeSend: function() {
                // setting a timeout
                $('#loadingImage').show();

            },
            success: function (response) {
                console.log(response.edit);
                // $('#loadingImage').hide();
                $("#edit").modal() // Buka Modal
                $('#product_id').val(params) // parameter
                $('#namaprod').val(response.edit.invoice)
                $('#prc').val(response.edit.prc)
                $('#kode_deltomed').val(response.edit.kode_deltomed)
                $('#unit').val(response.edit.isisatuan)
                $('#odr_unit').val(response.edit.odrunit)
                $('#group2').val(response.edit.varian_id)
                $('#sub_group2').val(response.edit.group_id)
                $('#qty1').val(response.edit.qty1)
                $('#qty2').val(response.edit.qty2)
                $('#qty3').val(response.edit.qty3)
                $('#kecil').val(response.edit.kecil)
                $('#sedang').val(response.edit.sedang)
                $('#besar').val(response.edit.besar)
                $('#kategori').val(response.edit.kategori_online)
                $("#kode_supp2").val(response.edit.supp)
                $("#b_kecil").val(response.edit.berat_gr)
                $("#b_besar").val(response.edit.berat)
                $("#volume").val(response.edit.volume)
                $("#l_image").val(response.edit.image)
                $("#img_view").attr('src', response.edit.image)

                $("#p_deskrip").val(response.edit.deskripsi).change();
            }
        });
        
        $(document).ready(function() {
            $("#kode_supp2").click(function(){
                $.ajax({
                url:"<?php echo base_url(); ?>master_product/build_group",    
                data: {kode_supp: $(this).val()},
                type: "POST",
                success: function(data){
                    $("#group2").html(data);
                    }
                });
            });

            $("#group2").click(function(){
                $.ajax({
                url:"<?php echo base_url(); ?>master_product/build_subgroup",    
                data: {grup: $(this).val()},
                type: "POST",
                success: function(data){
                    $("#sub_group2").html(data);
                    }
                });
            });
        });
    }
</script>
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
       
        <?php echo form_open('master_product/edit_product/'); ?>
        <div class="modal-body">
        <!-- <p id="loadingImage" style="font-size: 60px; display: none ">Loading ...</p> -->

            <div class="form-group row">
                <label class="col-sm-4">Product Code</label>

                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kodeprod" id="product_id" required readonly />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Product Name</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="namaprod" id="namaprod" required />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">PRC Code</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="prc" id="prc"  required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Kode Deltomed</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kd_delto" id="kode_deltomed"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Supplier</label>
                <div class="col-sm-6">
                <?php
                        foreach($suppq->result() as $value)
                    { 
                        $supp[$value->supp]= $value->namasupp;
                    }
                    echo form_dropdown('supp', $supp,'','id="kode_supp2" class="form-control"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Group</label>
                <div class="col-sm-6">
                <?php
                    $grup=array();
                    echo form_dropdown('group', $grup,'',' id="group2" class="form-control"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Sub Group</label>
                <div class="col-sm-6">
                <?php
                    $s_group=array();
                    echo form_dropdown('s_group', $s_group,'','id="sub_group2" class="form-control"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Kategori</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kategori" id="kategori" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Qty 1 (*Satuan Kecil)</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="qty1" id="qty1"/>
                </div>
                Jenis <div class="col-sm-3">
                <?php
                        foreach($jenis->result() as $jk)
                    {
                        $j_kecil[$jk->kecil]= $jk->kecil;
                    }
                    echo form_dropdown('j_kecil', $j_kecil,'','class="form-control" id="kecil"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Qty 2 (*Satuan Sedang)</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="qty2" id="qty2"/>
                </div>
                Jenis <div class="col-sm-3">
                <?php
                        foreach($jenis->result() as $js)
                    {
                        $j_sedang[$js->sedang]= $js->sedang;
                    }
                    echo form_dropdown('j_sedang', $j_sedang,'','class="form-control" id="sedang"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Qty 3 (*Satuan Besar)</label>
                <div class="col-sm-2">
                    <input class="form-control" type="number" name="qty3" id="qty3" />
                </div>
                Jenis <div class="col-sm-3">
                <?php
                        foreach($jenis->result() as $jb)
                    {
                        $j_besar[$jb->besar]= $jb->besar;
                    }
                    echo form_dropdown('j_besar', $j_besar,'','class="form-control" id="besar"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Unit</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="unit" id="unit" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Order Unit</label>
                <div class="col-sm-6">
                <?php
                        foreach($jenis->result() as $o_unit)
                    {
                        $odr_unit[$o_unit->odrunit]= $o_unit->odrunit;
                    }
                    echo form_dropdown('odr_unit', $odr_unit,'','class="form-control" id="odr_unit"');
                ?>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Berat Gr (*Satuan Kecil)</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="b_kecil" id="b_kecil"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Berat (*Satuan Besar)</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="b_besar" id="b_besar"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Volume</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="volume" id="volume" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Link Image</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="l_image" id="l_image"/>
                    <br>
                    <img alt="" id="img_view" style='max-width: 60%;'>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Product Description</label>
                <div class="col-sm-6">
                    <!-- <input class="form-control" type="text" name="berat"/> -->
                    <textarea name="p_deskrip" rows="7" cols="50" id="p_deskrip"></textarea>
                </div>
            </div>
            
        </div>
        <div class="modal-footer">
            <?php echo form_submit('submit','Simpan', 'class="btn btn-success"');?>
            <?php echo form_close();?>
        </div>
        </div>
    </div>
</div>