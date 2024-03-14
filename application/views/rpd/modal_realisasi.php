<div class="modal fade" id="realisasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">RPD | Realisasi</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url().'rpd/proses_realisasi';?>" method="post" enctype="multipart/form-data">
                <div class="modal-body" id="form_realisasi">
                    <input class="form-control" type="text" name="id_realisasi" id="id_realisasi" hidden/>
                    <input class="form-control" type="text" name="id_rpd_realisasi" value="<?= $get_history->id; ?>" hidden/>
                    <input type="text" name="signature_realisasi" value="<?= $this->uri->segment('3');?>" hidden>
                    <input class="form-control" type="text" name="id_aktivitas_realisasi[]" id="id_aktivitas_realisasi" hidden/>
                    <div class="form-group row">
                        <label class="col-sm-4">Pilih Aktivitas</label>
                        <div class="col-sm-6">
                            <select name="rencana[]" id="rencana_realisasi" class="form-control"></select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Tanggal Aktivitas</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="datetime-local" name="tanggal_aktivitas_realisasi[]" id="tanggal_aktivitas_realisasi" readonly/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Tanggal Realisasi</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="datetime-local" name="tanggal_realisasi[]" id="tanggal_realisasi" required/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Detail Aktivitas</label>
                        <div class="col-sm-6">
                            <textarea name="detail_aktivitas_realisasi[]" id="detail_aktivitas_realisasi" class="form-control"
                                cols="30" rows="3"
                                placeholder="Isi Detail Aktivitas" readonly></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Detail Realisasi</label>
                        <div class="col-sm-6">
                            <textarea name="detail_realisasi[]" id="detail_realisasi" class="form-control"
                                cols="30" rows="3"
                                placeholder="Isi Detail Aktivitas" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Kategori Pengeluaran Aktivitas</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text"
                                name="jenis_pengeluaran_aktivitas_realisasi[]" id="jenis_pengeluaran_aktivitas_realisasi" placeholder="Isi Pengeluaran" readonly/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Kategori Pengeluaran Realisasi</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text"
                                name="jenis_pengeluaran_realisasi[]" id="jenis_pengeluaran_realisasi" placeholder="Isi Pengeluaran" required/>
                        </div>
                    </div>

                    <div class="form-group row" hidden>
                        <label class="col-sm-4">Limit Budget</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number"
                                name="limit_budget_realisasi[]" id="limit_budget_realisasi" placeholder="Contoh: 100000" />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Nominal Biaya Aktivitas</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number"
                                name="nominal_biaya_aktivitas_realisasi[]" id="nominal_biaya_aktivitas_realisasi" placeholder="Contoh: 100000" readonly/>
                        </div>
                    </div>

                    <div class="form-group row" id="select_biaya_realisasi">
                        <label class="col-sm-4"></label>
                        <div class="col-sm-6">
                            <input type="radio" id="btn_ya_biaya_realisasi" name="select_biaya_realisasi" checked>
                            <label for="btn_ya_biaya_realisasi">Ya, Butuh Biaya</label> <br>
                            <input type="radio" id="btn_tidak_biaya_realisasi" name="select_biaya_realisasi">
                            <label for="btn_tidak_biaya_realisasi">Tidak, Butuh Biaya</label><br>
                        </div>
                    </div>

                    <div class="form-group row" id="nominal_biaya_realisasi">
                        <label class="col-sm-4">Nominal Biaya Realisasi</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number"
                                name="nominal_biaya_realisasi[]" id="nominal_biaya_realisasi" placeholder="Contoh: 100000" required/>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Keterangan Aktivitas</label>
                        <div class="col-sm-6">
                            <textarea name="keterangan_aktivitas_realisasi[]" id="keterangan_aktivitas_realisasi" class="form-control"
                                cols="30" rows="5"
                                placeholder="Isi Detail Keterang" readonly></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Keterangan Realisasi</label>
                        <div class="col-sm-6">
                            <textarea name="keterangan_realisasi[]" id="keterangan_realisasi" class="form-control"
                                cols="30" rows="5"
                                placeholder="Isi Detail Keterang" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Foto/Capture Bill / Struk (.jpg)</label>
                        <div class="col-sm-6">
                            <input type="file" name="foto_struk[]" id="foto_struk" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4"></label>
                    <div class="col-sm-6">
                        <a href="#" class="btn btn-warning btn-sm" id="btn_add_realisasi">+ Tambah</a>
                    </div>
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-success" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let index = 1;
        // var userid = document.getElementById("userid").value;
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url('rpd/get_aktivitas');?>',
            data:{
                id:<?= $get_history->id; ?>
            },
            success: function (hasil_aktivitas) {
                $("select#rencana_realisasi").html(hasil_aktivitas);
                $("select#rencana_realisasi").on("change", function () {
                    var rencana = document.getElementById("rencana_realisasi").value;

                    // console.log(rencana)

                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url('rpd/get_data') ?>',
                        data: {
                            id: rencana,
                        },
                        dataType: "json",
                        success: function (response) {
                            console.log(response.get_aktivitas);
                            $("#id_aktivitas_realisasi").val(response.get_aktivitas.id)
                            $("#tanggal_aktivitas_realisasi").val(response.get_aktivitas.tanggal)
                            $("#detail_aktivitas_realisasi").val(response.get_aktivitas.detail)
                            $('#jenis_pengeluaran_aktivitas_realisasi').val(response.get_aktivitas.nama_kategori)
                            $('#limit_budget_realisasi').val(response.get_aktivitas.limit_budget)
                            $('#nominal_biaya_aktivitas_realisasi').val(response.get_aktivitas.nominal_biaya)
                            $('#keterangan_aktivitas_realisasi').val(response.get_aktivitas.keterangan)

                            $("#tanggal_realisasi").val(response.get_aktivitas.tanggal)
                            $("#detail_realisasi").val(response.get_aktivitas.detail)
                            $('#jenis_pengeluaran_realisasi').val(response.get_aktivitas.nama_kategori)
                            $('input#nominal_biaya_realisasi').val(response.get_aktivitas.nominal_biaya)
                            $('#keterangan_realisasi').val(response.get_aktivitas.keterangan)
                                .change()
                        }
                    });
                });
            }
        });
        
        // function button dan append

        $("#btn_ya_biaya_realisasi").on("change", function () {
            $("div#nominal_biaya_realisasi").show();
            $('input#foto_struk').attr("required", true);
        });

        $("#btn_tidak_biaya_realisasi").click(function () {
            $("div#nominal_biaya_realisasi").hide();
            $("input#nominal_biaya_realisasi").val('0');
            $('input#foto_struk').attr("required", false);
        });
        
        $("#btn_add_realisasi").click(function () {
            var i = index;

            var id_aktivitas = 
                '<input class="form-control" type="text" name="id_aktivitas_realisasi[]" id="id_aktivitas_realisasi'.concat(index,'" hidden/>')

            var rencana_aktivitas = 
                '<div class="form-group row"><label class="col-sm-4">Pilih Rencana</label><div class="col-sm-6"><select name="rencana[]" id="rencana_realisasi'.concat(index,'" class="form-control"></select></div></div>')

            var tanggal_aktivitas = 
                '<div class="form-group row"><label class="col-sm-4">Tanggal Aktivitas</label><div class="col-sm-6"><input class="form-control" type="datetime-local" name="tanggal_aktivitas_realisasi[]" id="tanggal_aktivitas_realisasi'.concat(index,'" readonly/></div></div>')

            var detail_aktivitas = 
                '<div class="form-group row"><label class="col-sm-4">Detail Aktivitas</label><div class="col-sm-6"><textarea name="detail_aktivitas_realisasi[]" id="detail_aktivitas_realisasi'.concat(index,'" class="form-control" cols="30" rows="3" placeholder="Isi Detail Aktivitas" readonly></textarea></div></div>')

            var jenis_aktivitas = 
                '<div class="form-group row"><label class="col-sm-4">Jenis Pengeluaran</label><div class="col-sm-6"><input class="form-control" type="text"name="jenis_pengeluaran_aktivitas_realisasi[]" id="jenis_pengeluaran_aktivitas_realisasi'.concat(index,'" placeholder="Isi Pengeluaran" readonly/></div></div>')

            var limit_budget = 
                '<div class="form-group row" hidden><label class="col-sm-4">Limit Budget</label><div class="col-sm-6"><input class="form-control" type="number" name="limit_budget_realisasi[]" id="limit_budget_realisasi'.concat(index,'" placeholder="Contoh: 100000" readonly/></div></div>')

            var nominal_aktivitas = 
                '<div class="form-group row"><label class="col-sm-4">Nominal Biaya Terealisasi</label><div class="col-sm-6"><input class="form-control" type="number" name="nominal_biaya_aktivitas_realisasi[]" id="nominal_biaya_aktivitas_realisasi'.concat(index,'" placeholder="Contoh: 100000" readonly/></div></div>')

            var keterangan_aktivitas = 
                '<div class="form-group row"><label class="col-sm-4">Keterangan</label><div class="col-sm-6"><textarea name="keterangan_aktivitas_realisasi[]" id="keterangan_aktivitas_realisasi'.concat(index,'" class="form-control" cols="30" rows="5" placeholder="Isi Detail Keterang" readonly></textarea></div></div>')

            var tanggal_realisasi = 
                '<div class="form-group row"><label class="col-sm-4">Tanggal Realisasi</label><div class="col-sm-6"><input class="form-control" type="datetime-local" name="tanggal_realisasi[]" id="tanggal_realisasi'.concat(index,'" required/></div></div>')

            var detail_realisasi= 
                '<div class="form-group row"><label class="col-sm-4">Detail Realisasi</label><div class="col-sm-6"><textarea name="detail_realisasi[]" id="detail_realisasi'.concat(index,'" class="form-control"style="text-transform: capitalize;" cols="30" rows="3" placeholder="Isi Detail Aktivitas" required></textarea></div></div>')

            var jenis_realisasi = 
                '<div class="form-group row"><label class="col-sm-4">Jenis Realisasi</label><div class="col-sm-6"><input class="form-control" type="text" name="jenis_pengeluaran_realisasi[]" id="jenis_pengeluaran_realisasi'.concat(index,'" placeholder="Isi Pengeluaran" required/></div></div>')

            var select_biaya = 
                '<div class="form-group row" id="select_biaya_realisasi'.concat(index,'"><label class="col-sm-4"></label><div class="col-sm-6"><input type="radio" id="btn_ya_biaya_realisasi'+index,'" name="select_biaya_realisasi'+index,'" checked ><label for="btn_ya_biaya_realisasi'+index,'">Ya, Butuh Biaya</label> <br><input type="radio" id="btn_tidak_biaya_realisasi'+index,'" name="select_biaya_realisasi'+index,'"><label for="btn_tidak_biaya_realisasi'+index,'">Tidak, Butuh Biaya</label><br></div></div>')

            var nominal_realisasi = 
                '<div class="form-group row" id="nominal_biaya_realisasi'.concat(index,'"><label class="col-sm-4">Nominal Biaya Realisasi</label><div class="col-sm-6"><input class="form-control" type="number" name="nominal_biaya_realisasi[]" id="nominal_biaya_realisasi'+index,'" placeholder="Contoh: 100000" required/></div></div>')

            var keterangan_realisasi = 
                '<div class="form-group row"><label class="col-sm-4">Keterangan Realisasi</label><div class="col-sm-6"><textarea name="keterangan_realisasi[]" id="keterangan_realisasi'.concat(index,'" class="form-control"style="text-transform: capitalize;" cols="30" rows="5" placeholder="Isi Detail Keterang" required></textarea></div></div>')

            var foto_struk = 
                '<div class="form-group row"><label class="col-sm-4">Foto/Capture Bill / Struk (.jpg)</label><div class="col-sm-6"><input type="file" name="foto_struk[]" id="foto_struk'.concat(index,'" class="form-control"></div></div>')

            $.ajax({
            type: 'POST',
                url: '<?php echo base_url('rpd/get_aktivitas');?>',
                data:{
                    id:<?= $get_history->id; ?>
                },
                success: function (hasil_aktivitas) {
                    $("select#rencana_realisasi"+i).html(hasil_aktivitas);
                    $("select#rencana_realisasi"+i).on("change", function () {
                        var rencana = document.getElementById("rencana_realisasi"+i).value;

                        // console.log(rencana)

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo base_url('rpd/get_data') ?>',
                            data: {
                                id: rencana,
                            },
                            dataType: "json",
                            success: function (response) {
                                console.log(response.get_aktivitas);
                                $("input#id_aktivitas_realisasi"+i).val(response.get_aktivitas.id)
                                $("input#tanggal_aktivitas_realisasi"+i).val(response.get_aktivitas.tanggal)
                                $("textarea#detail_aktivitas_realisasi"+i).val(response.get_aktivitas.detail)
                                $('input#jenis_pengeluaran_aktivitas_realisasi'+i).val(response.get_aktivitas.nama_kategori)
                                $('input#limit_budget_realisasi'+i).val(response.get_aktivitas.limit_budget)                    
                                $('input#nominal_biaya_aktivitas_realisasi'+i).val(response.get_aktivitas.nominal_biaya)
                                $('textarea#keterangan_aktivitas_realisasi'+i).val(response.get_aktivitas.keterangan)

                                $("input#tanggal_realisasi"+i).val(response.get_aktivitas.tanggal)
                                $("textarea#detail_realisasi"+i).val(response.get_aktivitas.detail)
                                $('input#jenis_pengeluaran_realisasi'+i).val(response.get_aktivitas.nama_kategori)
                                $('input#nominal_biaya_realisasi'+i).val(response.get_aktivitas.nominal_biaya)
                                $('textarea#keterangan_realisasi'+i).val(response.get_aktivitas.keterangan)
                                $('input#foto_struk'+i).attr("required", true)
                                    .change()
                            }
                        });
                    });
                }
            });

            $("div#form_realisasi").append('<div class="append_realisasi">'.concat('<hr>',id_aktivitas,rencana_aktivitas,rencana_realisasi,tanggal_aktivitas,tanggal_realisasi,detail_aktivitas,detail_realisasi,jenis_aktivitas,limit_budget,nominal_aktivitas,jenis_realisasi,select_biaya,nominal_realisasi,keterangan_aktivitas,keterangan_realisasi,foto_struk));
            
            $("#btn_ya_biaya_realisasi"+i).on("change", function () {
                $("div#nominal_biaya_realisasi"+i).show();
                $('input#foto_struk'+i).attr("required", true);
            });

            $("#btn_tidak_biaya_realisasi"+i).click(function () {
                $("div#nominal_biaya_realisasi"+i).hide();
                $("input#nominal_biaya_realisasi"+i).val('0');
                $('input#foto_struk'+i).attr("required", false);
            });

            index++;
        });
    });

    function addRealisasi() {
        $("#realisasi").modal() // Buka Modal
        $(".append_realisasi").remove();
        $('input#id_realisasi').val('')
        $('select#rencana_realisasi').val('').attr("required", true)
        $('input#tanggal_aktivitas_realisasi').val('')
        $('textarea#detail_aktivitas_realisasi').val('')
        $('input#jenis_pengeluaran_aktivitas_realisasi').val('')
        $('input#limit_budget_realisasi').val('')
        $('input#nominal_biaya_aktivitas_realisasi').val('')
        $('textarea#keterangan_aktivitas_realisasi').val('')
        $('input#foto_struk').val('').attr("required", true)
            .change();
    }

</script>