<!-- modal tambah profile -->
<div class="modal fade" id="tambah_surat_jalan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Generate Report Retur (PDF)</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="report_retur" method="POST">
                <div class="modal-body">

                    <div class="form-group row">
                        <label class="col-sm-3">Bulan Tahun</label>
                        <div class="col-sm-8">
                            <input type="month" name="bulan" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3">Principal</label>
                        <div class="col-sm-8">
                            <select class="form-control kategori" name="principal" required>
                                <option value=""> -- Pilih Salah Satu -- </option>
                                <option value="all">ALL Principal</option>
                                <option value="001">Deltomed</option>
                                <option value="005">US</option>
                            </select>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="submit">

            </form>


        </div>
    </div>
</div>
</div>

<script>
    function openModalReportRetur() {

        $.ajax({
            success: function(response) {
                $("#tambah_surat_jalan").modal()
            }
        });
    }
</script>