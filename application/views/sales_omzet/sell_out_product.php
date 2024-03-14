<?php
$supplier = array();
foreach ($get_supp->result() as $value) {
    $supplier[$value->supp] = $value->namasupp;
}

$group = array();
$group['0'] = '--';

$interval = date('Y') - 2015;
$options = array();
for ($i = 1; $i <= $interval; $i++) {
    $options['' . $i + 2015] = '' . $i + 2015;
}

$kondisi_class = array();
$kondisi_class['1'] = 'Pencatatan di Faktur Transaksi (default)';
$kondisi_class['2'] = 'Data Terbaru (current)';

$custom_outlet = array();
$custom_outlet['1'] = 'tabel outlet mpm (default)';
$custom_outlet['2'] = 'tabel outlet deltomed';

echo form_open($url);
?>

<div class="card">
    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tahun</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php
                    echo form_dropdown('year', $options, date('Y'), 'class="form-control"');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Class Outlet Berdasarkan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php
                    echo form_dropdown('kondisi_class', $kondisi_class, 'UNIT', 'class="form-control"');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tabel Outlet &nbsp;
                <?php if ($this->session->userdata('supp') == '001' || $this->session->userdata('id') == '297') { ?>
                    <a href="#" data-toggle="modal" data-target="#uploadModal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                            <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z" />
                            <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z" />

                        </svg></a>
                <?php } else {
                } ?>
            </label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php
                    echo form_dropdown('custom_outlet', $custom_outlet, 'UNIT', 'class="form-control"');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Satuan</label>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <select name="satuan" class="form-control">
                        <option value="1">Kecil</option>
                        <option value="2">Sedang</option>
                        <option value="3">Besar</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Breakdown</label>
            <div class="col-sm-10">
                <div class="col-sm-10">

                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox1" type="checkbox" name="breakdown_kode" value="1">
                        <label for="checkbox1">
                            SubBranch
                        </label>
                    </div>

                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox2" type="checkbox" name="breakdown_kodeprod" value="1">
                        <label for="checkbox2">
                            Kode produk
                        </label>
                    </div>

                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox3" type="checkbox" name="breakdown_kodesalur" value="1">
                        <label for="checkbox3">
                            Class Outlet
                        </label>
                    </div>

                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox4" type="checkbox" name="breakdown_type" value="1">
                        <label for="checkbox4">
                            Tipe Outlet
                        </label>
                    </div>

                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox5" type="checkbox" name="breakdown_group" value="1">
                        <label for="checkbox5">
                            Group Product
                        </label>
                    </div>

                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox6" type="checkbox" name="breakdown_subgroup" value="1">
                        <label for="checkbox6">
                            Sub Group
                        </label>
                    </div>

                    <div class="checkbox-color checkbox-primary">
                        <input id="checkbox7" type="checkbox" name="breakdown_salesman" value="1">
                        <label for="checkbox7">
                            Salesman
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group row">
            <div class="col-sm-2"></div>
            <div class="col-sm-9">
                <div class="col-sm-6">
                    <?php echo form_submit('submit', 'Proses', 'onclick="return ValidateCompare();" class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
        <hr>
        <p style="font-size: 15px; color:red;"><?= ucwords('note : web terkadang tidak menampilkan seluruh data yang anda request ke dalam tabel. Untuk itu, sebaiknya lakukan export setelah data berhasil di proses'); ?></p>
    </div>


    <hr>
    <?php
    $this->load->view('templates/layout_button_produk');
    ?>
    <hr>
</div>

</div>

<!-- =============================================== modal ======================================================== -->

<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Override Sektor Deltomed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_open('sales_omzet/export_mapping_sektor'); ?>
                <div class="col-sm-12">
                    <?php echo form_submit('submit', 'Export Data (..1)', 'class="btn btn-warning btn-sm"'); ?>
                </div>
                <?php echo form_close(); ?>
                <br>

                <?php echo form_open_multipart('sales_omzet/import_mapping_sektor'); ?>
                <div class="col-sm-12">
                    <div class="form-inline row">
                        <div class="col-sm-12">
                            <input type="file" name="file" class="form-control" required>
                            <?php echo form_submit('submit', 'Update Data ..2', 'class="btn btn-primary btn-sm"'); ?>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>


            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".loading").hide();
            $(".submit").click(function() {
                $(".loading").show();
                $(".submit").hide();
            });
        });
    </script>