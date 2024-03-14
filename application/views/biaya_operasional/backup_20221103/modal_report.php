<!-- Modal -->
<div class="modal fade" id="report" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Report Biaya Operasional</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('biaya_operasional/report');?>
                <div class="row admin">
                    <label class="col-sm-3">User</label>
                    <div class="col-sm-9">
                        <?php 
                            foreach($list_user as $a) 
                            {
                                $user ['0']= '-- Pilih --' ;
                                $user[$a->id]= $a->username;
                            }
                            echo form_dropdown('user', $user,'','class="form-control"');
                        ?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <label class="col-sm-3">Tanggal</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="date" name="from" required />
                    </div>
                    To
                    <div class="col-sm-4">
                        <input class="form-control" type="date" name="to" required />
                    </div>
                </div>
                <br>
                <div align="center">
                    <button type="submit" name="submit" value="1" class="btn btn-success btn-sm">Export (.csv)</button>
                    <button type="submit" name="submit" value="2" class="btn btn-warning btn-sm">Export (.pdf)</button>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var user = <?= $this->session->userdata('id')?>

        if(user == '297' || user == '561' || user == '444' || user == '231' || user == '547' ||
            user == '18' || user == '557' || user == '362') {
            $('.admin').show()
        }else{
            $('.admin').hide()
        }
</script>