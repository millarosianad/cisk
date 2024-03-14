<div class="card-block">
    <div class='row'>
        <div class="col-md-12">
            <div class="form-group">
                <?php
                //echo "<pre>";
                //echo "<h1>omzet : Rp. ".$omzet."</h1><br>";
                //echo "</pre>";
                echo "<pre>";
                echo "<h3 style='text-align:center;'> ~ PREVIEW UPLOAD OMZET ~</h3>";
                echo "Anda sudah meng-Upload Data sebagai berikut :<br>";
                echo "- nocab : " . $nocab . "<br>";
                echo "- tahun : " . $tahun . "<br>";
                echo "- bulan : " . $bulan . "<br>";
                echo "- tanggal : " . $tanggal . "<br>";
                echo "- filename : " . $filenamezip . "<br>";

                echo "- omzet : <strong><font size = 4px>Rp " . $omzet . "</font></strong><br>";
                echo "</pre>";
                echo "<h3>Apakah omzet sesuai ?</h3> <br>";
                echo "- Jika total omzet sudah benar, klik tombol di bawah ini <br>";
                echo " &nbsp;   "; ?>

                <button type="button" class="btn btn-primary btn-sm btn-round" id="btnKirim" data-toggle="modal" data-target="#exampleModal">
                    Submit
                </button>

                <?php
                echo "<br><br>- jika tidak, silahkan <strong> Upload ulang / hubungi IT </strong> <br>";

                ?>

            </div>
        </div>
    </div>
</div>

<!-- ========================================================== MODAL ========================================================== -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Total Omzet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open("upload_file/prosesOmzet/$nocab/$tahun/$bulan/$tanggal/$id"); ?>
                <div class="row">
                    <div class="col-md-6">
                        <h4>Omzet :</h4>
                    </div>
                    <div class="col-md-6">
                        <h4>Rp <?= $omzet; ?></h4>
                    </div>
                    <br>
                    <br>
                    <div class="col-md-6"><strong>Apakah ini data closing di bulan ini ?</strong></div>
                    <div class="col-md-6">
                        <select name="status_closing" class="form-control">
                            <option value="null">Pilih</option>
                            <option value="0">Bukan Closing Bulan Ini</option>
                            <option value="1" name=>Ya, Closing Bulan Ini</option>
                        </select>
                    </div>
                    <div class="col-md-12 loading" align="center">
                        <img src="<?= base_url() . 'assets/gif/loading.gif' ?>" alt="">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button class="btn btn-round btn-sm btn-success submit">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".loading").hide();
        $(".submit").hide();
        $(function() {
            $('select[name=status_closing]').on('change', function() {
                var status = $(this).children("option:selected").val();
                if (status == 'null') {
                    $(".submit").hide();
                } else {
                    $(".submit").show();
                }
            });
        });
        $(".submit").click(function() {
            $(".loading").show();
            $(".submit").hide();
        });
    });
</script>