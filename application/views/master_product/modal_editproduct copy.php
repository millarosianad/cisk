
<div class="modal fade editProduct" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Tambah Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group row">
                <label class="col-sm-4">Product Code</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kodeprod" required id="product_id"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Product Name</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="namaprod" value="" required id="namaprod" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">PRC Code</label>
                <div class="col-sm-6">
                    <input id="prc" class="form-control" type="text" name="prc" value="" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Group Product</label>
                <div class="col-sm-6">
                    <input id="gp" class="form-control" type="text" name="gp" value=""/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Content Unit (*Satuan Terkecil)</label>
                <div class="col-sm-6">
                    <input id="c_unit" class="form-control" type="text" name="c_unit" value="" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Unit</label>
                <div class="col-sm-6">
                    <input id="unit" class="form-control" type="text" name="unit" value="" required/>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-4">Order Unit</label>
                <div class="col-sm-6">
                    <input id="o_unit" class="form-control" type="text" name="o_unit" value="" required/>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4">Supplier</label>
                <div class="col-sm-6">
                    <select class="form-control" name="supp" id="kode_supp">
                    <?php foreach ($suppq->result() as $row) { ?>
                        
                    <option value="<?= $row->supp ?>" ><?= $row->namasupp ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-4">Gambar</label>
                <div class="col-sm-6">
                    <input type="file" name="image" class="form-control" accept=".png, .jpg, .jpeg" >
                </div>
            </div>

            <div class="modal-footer">
                <?php echo form_open_multipart('assets/input_assets_hasil/'); ?>
                <?php echo form_submit('submit','Simpan', 'class="btn btn-success"');?>
                <?php echo form_close();?>
            </div>
            </div>
        </div>
    </div>
</div>