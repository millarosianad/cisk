<!-- Modal -->
<div class="modal fade" id="note" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url($url2);?>" method="post">
                <div class="modal-body">
                    <div hidden>
                        <input type="text" value="<?= $get_status_pengajuan[0]->signature;?>" name="signature">
                        <input type="text" value="<?= $get_status_pengajuan[0]->supp;?>" name="supp">
                    </div>
                    <div class="row">
                        <div class="col-2">
                            Note
                        </div>
                        <div class="col-10">
                            <Textarea class="form-control" rows="5" name="note" id="note"></Textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>