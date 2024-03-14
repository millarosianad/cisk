<div class="modal fade" id="aktivitas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">RPD | Rencana Aktivitas</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url().'rpd/proses_aktivitas';?>" method="post">
                <div class="modal-body" id="form_aktivitas">
                    <input class="form-control" type="text" name="id" id="id" hidden />
                    <input class="form-control" type="text" name="id_rpd" value="<?= $get_history->id; ?>" hidden />
                    <input type="text" name="signature" value="<?= $this->uri->segment('3');?>" hidden>
                    <div class="form-group row">
                        <label class="col-sm-4">Rencana Aktivitas</label>
                        <div class="col-sm-6">
                            <textarea name="rencana[]" id="rencana" class="form-control" cols="30" rows="3"
                                placeholder="Isi Rencana Aktivitas" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Tanggal Aktivitas</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="datetime-local" name="tanggal[]" id="tanggal" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Detail Aktivitas</label>
                        <div class="col-sm-6">
                            <textarea name="detail[]" id="detail" class="form-control" cols="30" rows="3"
                                placeholder="Isi Detail Aktivitas" required></textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Kategori Pengeluaran</label>
                        <div class="col-sm-6">
                            <select name="jenis_pengeluaran[]" id="jenis_pengeluaran"
                                class="form-control jenis_pengeluaran">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" >
                        <label class="col-sm-4">Limit Budget</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" name="limit_budget[]" id="limit_budget" min="0" placeholder="Contoh: 100000" readonly />
                        </div>
                    </div>

                    <div class="form-group row" id="select_aktivitas">
                        <label class="col-sm-4"></label>
                        <div class="col-sm-6">
                            <input type="radio" id="btn_ya_biaya" name="select_biaya">
                            <label for="btn_ya_biaya">Ya, Butuh Biaya</label> <br>
                            <input type="radio" id="btn_tidak_biaya" name="select_biaya">
                            <label for="btn_tidak_biaya">Tidak, Butuh Biaya</label><br>
                        </div>
                    </div>

                    <div class="form-group row" id="nominal_biaya_aktivitas">
                        <label class="col-sm-4">Nominal Biaya Yang Dibutuhkan</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="number" name="nominal_biaya[]" min="0" id="nominal_biaya"
                                placeholder="Contoh: 100000" required />
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4">Keterangan</label>
                        <div class="col-sm-6">
                            <textarea name="keterangan[]" id="keterangan" class="form-control" cols="30" rows="5"
                                placeholder="Isi Detail Keterangan" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4"></label>
                    <div class="col-sm-6">
                        <a href="#" class="btn btn-warning btn-sm" id="btn_add_aktivitas">+ Tambah</a>
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
        // var userid = 547;
        $("div#nominal_biaya_aktivitas").hide();
        $("div#select_aktivitas").hide();

        $.ajax({
            type: 'POST',
            url: '<?= base_url('rpd/kategori_biaya') ?>',
            // data: 'userid='+userid,
            success: function (hasil_kategori) {
                $("select#jenis_pengeluaran").html(hasil_kategori);
                $("select#jenis_pengeluaran").on("change", function () {
                    var jenis_pengeluaran = document.getElementById("jenis_pengeluaran").value;
                    // console.log(jenis_pengeluaran)
                    var jenis_terpilih = $("option:selected", this).attr("jenis");
                    
                    if (jenis_pengeluaran == '1') {
                        $("div#nominal_biaya_aktivitas").hide();
                        $("div#select_aktivitas").show();
                    }else{
                        $("div#nominal_biaya_aktivitas").show();
                        $("div#select_aktivitas").hide();
                    }
                    
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('rpd/limit_budget') ?>',
                        data: {
                            kategori_id: jenis_pengeluaran,
                        },
                        success: function (hasil_limit) {
                            $("#limit_budget").val(hasil_limit);
                        }
                    });
                });
            }
        });

        // function button dan append

        $("#btn_ya_biaya").on("change", function () {
            $("div#nominal_biaya_aktivitas").show();
        });

        $("#btn_tidak_biaya").click(function () {
            $("div#nominal_biaya_aktivitas").hide();
            $("input#nominal_biaya").val('0');
        });

        $("#btn_add_aktivitas").click(function () {
            // var userid = 547;
            var i = index;

            var rencana =
            '<div class="form-group row"><label class="col-sm-4">Rencana Aktivitas</label><div class="col-sm-6"><textarea name="rencana[]" id="rencana" class="form-control" cols="30" rows="3" placeholder="Isi Rencana Aktivitas" required></textarea></div></div>'

            var tanggal =
                '<div class="form-group row rencana"> <label class="col-sm-4">Tanggal Aktivitas</label> <div class="col-sm-6"> <input class="form-control" type="datetime-local" name="tanggal[]" id="tanggal" required /> </div></div>';

            var detail_aktivitas =
                '<div class="form-group row"><label class="col-sm-4">Detail Aktivitas</label><div class="col-sm-6"><textarea name="detail[]" id="detail" class="form-control" cols="30" rows="3" placeholder="Isi Detail Aktivitas" required></textarea></div> </div>'

            var jenis =
                '<div class="form-group row"><label class="col-sm-4">Jenis Pengeluaran</label><div class="col-sm-6"><select name="jenis_pengeluaran[]" id="jenis_pengeluaran'.concat(index,'" class="form-control jenis_pengeluaran'+index,'"></select></div></div>')

            var limit_budget =
                '<div class="form-group row"><label class="col-sm-4">Limit Budget</label><div class="col-sm-6"><input class="form-control limit_budget" type="number" name="limit_budget[]" min="0" id="limit_budget'.concat(index,'" placeholder="Contoh: 100000" readonly /></div></div>')

            var select_biaya = 
                '<div class="form-group row" id="select_aktivitas'.concat(index,'"><label class="col-sm-4"></label><div class="col-sm-6"><input type="radio" id="btn_ya_biaya'+index,'" name="select_biaya'+index,'"><label for="btn_ya_biaya'+index,'">Ya, Butuh Biaya</label> <br><input type="radio" id="btn_tidak_biaya'+index,'" name="select_biaya'+index,'"><label for="btn_tidak_biaya'+index,'">Tidak, Butuh Biaya</label><br></div></div>')

            var nominal_budget =
                '<div class="form-group row" id="nominal_biaya_aktivitas'.concat(index,'"><label class="col-sm-4">Nominal Biaya Yang Dibutuhkan</label><div class="col-sm-6"><input class="form-control" type="text" name="nominal_biaya[]" min="0" id="nominal_biaya'+index,'" placeholder="Contoh: 100000" required/></div></div>')

            var keterangan =
                '<div class="form-group row"><label class="col-sm-4">Keterangan</label><div class="col-sm-6"><textarea name="keterangan[]" id="keterangan" class="form-control" cols="30"rows="5" placeholder="Isi Detail Keterangan" required></textarea></div></div>'
            
            $.ajax({
                type: 'POST',
                url: '<?= base_url('rpd/kategori_biaya') ?>',
                // data: 'userid='+userid,
                success: function (hasil_kategori) {
                    $("select#jenis_pengeluaran"+i).html(hasil_kategori);
                    $("div#nominal_biaya_aktivitas"+i).hide();
                    $("div#select_aktivitas"+i).hide();
            
                    $("select#jenis_pengeluaran"+i).on("change", function () {
                        var jenis_pengeluaran = document.getElementById("jenis_pengeluaran"+i).value;
                        var jenis_terpilih = $("option:selected", this).attr("jenis");
                        
                        if (jenis_pengeluaran == '1') {
                            $("div#nominal_biaya_aktivitas"+i).hide();
                            $("div#select_aktivitas"+i).show();
                        }else{
                            $("div#nominal_biaya_aktivitas"+i).show();
                            $("div#select_aktivitas"+i).hide();
                        }

                        $.ajax({
                            type: 'POST',
                            url: '<?= base_url('rpd/limit_budget') ?>',
                            data: {
                                kategori_id: jenis_pengeluaran,
                            },
                            success: function (hasil_limit) {
                                $("#limit_budget"+i).val(hasil_limit);
                            }
                        });
                    });
                }
            });
            

            $("div#form_aktivitas").append('<div class="append_aktivitas">'.concat('<hr>',rencana,tanggal,detail_aktivitas,jenis,limit_budget,select_biaya,nominal_budget,keterangan));

            $("#btn_ya_biaya"+i).on("change", function () {
                $("div#nominal_biaya_aktivitas"+i).show();
            });

            $("#btn_tidak_biaya"+i).click(function () {
                $("div#nominal_biaya_aktivitas"+i).hide();
                $("input#nominal_biaya"+i).val('0');
            });

            index++;
        });
    });

    function addAktivitas() {
        $("#aktivitas").modal()
        $('#btn_add_aktivitas').show()
        $(".append_aktivitas").remove();
        $("div#nominal_biaya_aktivitas").hide();
        $("div#select_aktivitas").hide();
        $('input#id').val() // parameter
        $('input#id_rpd').val('')
        $('input#signature').val('')
        $('textarea#rencana').val('')
        $('input#tanggal').val('')
        $('textarea#detail').val('')
        $('select#jenis_pengeluaran').val('')
        $('input#limit_budget').val('')
        $('input#nominal_biaya').val('')
        $('textarea#keterangan').val('')
    }

    function editAktivitas(params) {
        $('#btn_add_aktivitas').hide()
        $(".append_aktivitas").remove();
        $("div#nominal_biaya_aktivitas").show();
        $.ajax({
            type: "POST",
            url: "<?= base_url('rpd/get_data') ?>",
            data: {
                id: params
            },
            dataType: "json",
            success: function (response) {
                console.log(response.get_aktivitas);
                var jenis_pengeluaran = response.get_aktivitas.jenis_pengeluaran;
                var nominal_biaya = response.get_aktivitas.nominal_biaya;
                $("#aktivitas").modal() // Buka Modal
                $('input#id').val(params) // parameter
                $('input#id_rpd').val(response.get_aktivitas.id_rpd)
                $('input#signature').val(response.get_aktivitas.signature)
                $('textarea#rencana').val(response.get_aktivitas.rencana)
                $('input#tanggal').val(response.get_aktivitas.tanggal)
                $('textarea#detail').val(response.get_aktivitas.detail)
                $('select#jenis_pengeluaran').val(response.get_aktivitas.jenis_pengeluaran)
                $('input#limit_budget').val(response.get_aktivitas.limit_budget)
                $('input#nominal_biaya').val(response.get_aktivitas.nominal_biaya)
                $('textarea#keterangan').val(response.get_aktivitas.keterangan)
                .change();
                
                if (jenis_pengeluaran == '1') {
                    $("div#select_aktivitas").show();
                    if (nominal_biaya > '0') {
                        $("input#btn_ya_biaya").attr("checked", true);
                        $("div#nominal_biaya_aktivitas").show();
                    } else {
                        $("input#btn_tidak_biaya").attr("checked", true);
                        $("div#nominal_biaya_aktivitas").hide();
                    }
                } else {
                    $("div#select_aktivitas").hide();
                }
            }
        });
    }
</script>