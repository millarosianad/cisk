<div class="pcoded-content">
    <div class="page-header card">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <span></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                </div>
            </div>

        </div>
    </div>

    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">

                <div class="page-body">
                    <div class="row">

                        <!-- ================================================================== Table Noted =================================================================== -->
                        <div class="col-md-12 col-xl-12">
                            <div class="card comp-card">
                                <div class="card-header">
                                    <h5>Note - <?= $id_ticket; ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <a href="<?= base_url() . 'helpdesk'; ?>" class="btn btn-dark btn-round btn-sm" type="role">Kembali</a>
                                            <div class="card-block">
                                                <div class="dt-responsive table-responsive">
                                                    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <font size="2px">Nama
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Pesan
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Image
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Tanggal
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($note as $a) : ?>
                                                                <tr>
                                                                    <td>
                                                                        <font size="2px"><?= $a->username; ?>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->note; ?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="#" onclick="getImage('<?= $a->id ?>')"><img alt="" src="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename1; ?>" style='max-width: 25%;'></a>
                                                                        <a href="#" onclick="getImage('<?= $a->id ?>')"><img alt="" src="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename2; ?>" style='max-width: 25%;'></a>
                                                                        <a href="#" onclick="getImage('<?= $a->id ?>')"><img alt="" src="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename3; ?>" style='max-width: 25%;'></a>
                                                                    </td>
                                                                    <td>
                                                                        <font size="2px"><?= $a->created_date; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>

                                                        </tbody>

                                                        <tfoot>
                                                            <tr>
                                                                <th>
                                                                    <font size="2px">Nama
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Pesan
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Image
                                                                </th>
                                                                <th>
                                                                    <font size="2px">Tanggal
                                                                </th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================================= Form Noted =================================================================== -->

                        <div class="col-md-12 col-xl-12" id="add-note">
                            <div class="card sale-card">
                                <div class="card-header">
                                    <h5>Note</h5>
                                </div>
                                <div class="card-block">
                                    <?php echo form_open_multipart('/helpdesk/add_note_helpdesk'); ?>
                                    <div class="form-group row" hidden>
                                        <label class="col-sm-2">id</label>
                                        <div class="col-sm-9">
                                            <input name="id_ticket" class="form-control" value="<?= $a->id_ticket; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Pesan</label>
                                        <div class="col-sm-9">
                                            <textarea id='pesan' name="pesan" rows="6" cols="90%" class="form-control" maxlength="150" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Pilih File</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userfile" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format (.jpg)</p>
                                            <input type="file" name="userfile2" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format (.jpg)</p>
                                            <input type="file" name="userfile3" class="form-control" required>
                                            <p style="color: rgb(0 0 0 / 30%);">Format (.jpg)</p>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="row" style="justify-content: center;">
                                    <?php echo form_submit('submit', 'Simpan', 'class="btn btn-success btn-round center-block" required'); ?>
                                    <?php echo form_close(); ?>
                                </div>
                                <br>
                            </div>
                        </div>

                        <!-- =========================================== View Image ============================================ -->
                        <?php $this->load->view('helpdesk/view_image'); ?>

                        <script>
                            $(document).ready(function () {
                                var status = <?= $a->status; ?>
                                
                                if(status = 2){
                                    $('#add-note').remove()
                                }else{
                                    $('#add-note').show()
                                }
                            });
                            
                            function getImage(params) {
                                $.ajax({
                                    type: "GET",
                                    url: "<?= base_url('helpdesk/get_ImagebyID') ?>",
                                    data: {
                                        id: params
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        console.log(response.image);

                                        $("#viewimage").modal() // Buka Modal
                                        $("#img_view1").attr('src', '<?= base_url(); ?>/assets/uploads/helpdesk/'.concat(response.image.filename1))
                                        $("#img_view2").attr('src', '<?= base_url(); ?>/assets/uploads/helpdesk/'.concat(response.image.filename2))
                                        $("#img_view3").attr('src', '<?= base_url(); ?>/assets/uploads/helpdesk/'.concat(response.image.filename3))
                                    }
                                });
                            }
                        </script>