<div class="col-12">
    <a href="<?php echo base_url()."ecommerce/export_csv/kontak"; ?>" type="button"
        class="btn btn-sm btn-round btn-success">Export (.csv)</a>
    <!-- <a href="<?php echo base_url()."ecommerce/export_xls/kontak"; ?>" type="button" class="btn btn-sm btn-round btn-success">Export (.xls)</a> -->
    <br>
    <div class="dt-responsive table-responsive">
        <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
            <thead>
                <tr>
                    <th width="1">
                        <font size="2px">
                            <center>Branch</center>
                        </font>
                    </th>
                    <th>
                        <font size="2px">
                            <center>Sub Branch</center>
                    </th>
                    <th>
                        <font size="2px">
                            <center>Provinsi</center>
                    </th>
                    <th>
                        <font size="2px">
                            <center>Status HO</center>
                    </th>
                    <th>
                        <font size="2px">
                            <center>No. Telp Whatsapp</center>
                    </th>
                    <th>
                    </th>
                </tr>
            </thead>
            <tbody>

                <?php foreach($subbranch as $x) : ?>
                <tr>
                    <td>
                        <font size="2px"><?php echo $x->branch_name; ?></font>

                    </td>
                    <td>
                        <font size="2px"><?php echo $x->nama_comp; ?></font>
                    </td>
                    <td>
                        <font size="2px"><?php echo $x->provinsi; ?></font>
                    </td>
                    <td>
                        <font size="2px">
                            <?php if($x->status_ho == 1){
                                echo "True";
                            }else{
                                echo "False";
                            }; ?>
                        </font>
                    </td>
                    <td>
                        <font size="2px"><?php echo $x->telp_wa; ?></font>
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm btn-round"
                            onclick="getEditKontak('<?= $x->site_code ?>')">Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('ecommerce/update_kontak_subbranch'); ?>
                <input type="text" class="form-control" id="site_code" name="site_code" hidden>
                <div class="row">
                    <div class="col-4">
                        No. Telp
                    </div>
                    <div class="col-8">
                        <input type="text" class="form-control" id="telp" name="telp">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block btn-sm" required'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function getEditKontak(params) {
        $.ajax({
            type: "GET",
            url: "<?= base_url('ecommerce/get_subbranch')?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.edit);
                // $('#loadingImage').hide();
                $("#edit").modal() // Buka Modal
                $('#site_code').val(params) // parameter
                $('#telp').val(response.edit.telp_wa)
            }
        });
    }
</script>