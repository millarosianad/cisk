<script>
    function getEdittabcomp(params){
        $.ajax({
            type: "GET",
            url: "<?= base_url('master_tabcomp/get_tabcompID');?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.edit);
                $('#edit').modal()
                $('#tabcomp_id').val(params)
                $('#kode_compID').val(response.edit.kode_comp)
                $('#nocabID').val(response.edit.nocab)
                $('#branch').val(response.edit.branch_name)
                $('#s_branch').val(response.edit.nama_comp)
                $('#kode_comp').val(response.edit.kode_comp)
                $('#nocab').val(response.edit.nocab)
                $('#sub').val(response.edit.sub)
                $('#status').val(response.edit.status)
                $('#group_repl').val(response.edit.group_repl)
                $('#customerid').val(response.edit.customerid)
                $('#urutan').val(response.edit.urutan)
                .change();
            }
        });
    }
</script>
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php echo form_open('master_tabcomp/edit_tabcomp'); ?>
        <div class="modal-body">
            <div class="form-group row">
            <input class="form-control" type="text" name="tabcomp_id" value="" id="tabcomp_id" hidden/>
            <input class="form-control" type="text" name="kodecomp_id" value="" id="kode_compID" hidden/>
            <input class="form-control" type="text" name="nocab_id" value="" id="nocabID" hidden/>

                <label class="col-sm-4">Branch</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="branch" value="" id="branch" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Sub Branch</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="s_branch" value="" id="s_branch" required/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Kode Comp</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="kode_comp" value="" id="kode_comp" minlength='3' maxlength='3'/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Nocab</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nocab" value="" id="nocab" minlength='2' maxlength='2'/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Sub</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="sub" value="" id="sub" minlength='2' maxlength='2'/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Group Repl</label>
                <div class="col-sm-6">
                    <input id="unit" class="form-control" type="text" name="group_r" value="" id="group_repl"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Customer Id</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="cust_id" value="" id="customerid"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4">Urutan</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="urutan" value="" id="urutan"/>
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