<div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
         <?php echo form_open('master_tabcomp/tambah_tabcomp'); ?>
        <div class="modal-body">
            <div class="form-group row">
                <label class="col-sm-4">Branch</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="branch" value="" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Sub Branch</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="s_branch" value="" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Kode Comp</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kode_comp" value="" minlength='3' maxlength='3'/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Nocab</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nocab" value="" minlength='2' maxlength='2'/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Sub</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="sub" value="" minlength='2' maxlength='2' />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Status</label>
                <div class="col-sm-6">
                    <input id="checkbox1" type="checkbox"  name="tipe_1" value="1">
                    <label for="checkbox1">
                    Sub Total
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Group Repl</label>
                <div class="col-sm-6">
                    <input id="unit" class="form-control" type="text" name="group_r" value="" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Customer Id</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="cust_id" value="" />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Urutan</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="urutan" value="" />
                </div>
            </div>
            
        </div>
        
        <div class="modal-footer">
            <?php echo form_submit('submit','Simpan', 'class="btn btn-success" required');?>
            <?php echo form_close();?>
        </div>
        </div>
    </div>
</div>