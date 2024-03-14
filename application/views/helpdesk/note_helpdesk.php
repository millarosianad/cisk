<style>
    tbody {
      display:block;
      max-height:300px;
      overflow-y:auto;
    }
    thead, tbody tr {
        display:table;
        width:100%;
        table-layout:fixed;
    }
    thead {
        width: calc( 100% - 1em )
    } 
    
    table th, table td { overflow: hidden !important; white-space: normal !important;}
</style>

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
                                    <h5>Note</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <a href="<?= base_url() . 'helpdesk'; ?>"
                                                class="btn btn-dark btn-round btn-sm" type="role">Kembali</a>
                                            <div class="card-block">
                                                <div class="card-block table-border-style">
                                                    <div class="table-responsive">
                                                        <table id="example" class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th><font size="2px">Nama</th>
                                                                <th><font size="2px">Pesan</th>
                                                                <th><font size="2px">Attachment</th>
                                                                <th><font size="2px">Video</th>
                                                                <th><font size="2px">Tanggal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($note as $a) : ?>
                                                            <tr>
                                                                <td><font size="2px"><?= $a->username; ?></td>
                                                                <td><font size="2px"><?= $a->note; ?></font></td>
                                                                <td>
                                                                    <?php 
                                                                        $format1 = substr($a->filename1,-3);
                                                                        $format2 = substr($a->filename2,-3);
                                                                        $format3 = substr($a->filename3,-3);
                                                                        $src1 = base_url()."/assets/uploads/helpdesk/$a->filename1";
                                                                        $src2 = base_url()."/assets/uploads/helpdesk/$a->filename2";
                                                                        $src3 = base_url()."/assets/uploads/helpdesk/$a->filename3";

                                                                        if ($format1 == "jpg"){
                                                                            $file1 = "<img src='$src1' style='max-width: 25%;'>";
                                                                        }elseif ($format1 == ""){
                                                                            $file1 = "";
                                                                        }else{
                                                                            $file1 = "<button class='btn btn-sm btn-success btn-round'>download</button>";
                                                                        }
                                                                        
                                                                        if ($format2 == "jpg"){
                                                                            $file2 = "<img src='$src2' style='max-width: 25%;'>";
                                                                        }elseif ($format2 == ""){
                                                                            $file2 = "";
                                                                        }else{
                                                                            $file2 = "<button class='btn btn-sm btn-success btn-round'>download</button>";
                                                                        }
                                                                        
                                                                        if ($format3 == "jpg"){
                                                                            $file3 = "<img src='$src3' style='max-width: 25%;'>";
                                                                        }elseif ($format3 == ""){
                                                                            $file3 = "";
                                                                        }else{
                                                                            $file3 = "<button class='btn btn-sm btn-success btn-round'>download</button>";
                                                                        }
                                                                    ?>
                                                                    <a href="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename1; ?>" target="_blank"><?= $file1?></a>
                                                                    <a href="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename2; ?>" target="blank"><?= $file2?></a>
                                                                    <a href="<?= base_url() . 'assets/uploads/helpdesk/' . $a->filename3; ?>" target="blank"><?= $file3?></a>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        if ($a->video) { ?>
                                                                            <video width="300" controls>
                                                                                <source src="<?= base_url().'assets/uploads/helpdesk/'.$a->video; ?>" type="video/mp4">
                                                                                Your browser does not support the video tag.
                                                                            </video>
                                                                        <?php }else{ echo "none";}
                                                                    ?>
                                                                    
                                                                </td>
                                                                <td width="20%"><font size="2px"><?= $a->created_date; ?></td>
                                                            </tr>
                                                            <?php endforeach; ?>

                                                        </tbody>
                                                        </table>
                                                    </div>
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
                                            <input name="id_tiket" class="form-control" value="<?= $a->id_tiket; ?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Pesan</label>
                                        <div class="col-sm-9">
                                            <!-- <textarea id='pesan' name="pesan" rows="6" cols="90%" class="form-control" maxlength="150" required></textarea> -->
                                            <!-- menghilangkan maxlength request linda -->
                                            <textarea id='pesan' name="pesan" rows="6" cols="90%" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Attachment</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userfile" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" style="color: rgb(0 0 0 / 30%);">(Opsional)</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userfile2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" style="color: rgb(0 0 0 / 30%);">(Opsional)</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="userfile3" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label" style="color: rgb(0 0 0 / 50%);">Video</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="video" class="form-control">
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

                                if (status == 2) {
                                    $('#add-note').remove()
                                } else {
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
                                    success: function (response) {
                                        console.log(response.image);
                                        $("#viewimage").modal() // Buka Modal
                                        $("#img_view1").attr('src',
                                            '<?= base_url(); ?>/assets/uploads/helpdesk/'.concat(
                                                response.image.filename1))
                                        $("#img_view2").attr('src',
                                            '<?= base_url(); ?>/assets/uploads/helpdesk/'.concat(
                                                response.image.filename2))
                                        $("#img_view3").attr('src',
                                            '<?= base_url(); ?>/assets/uploads/helpdesk/'.concat(
                                                response.image.filename3))
                                    }
                                });
                            }
                        </script>