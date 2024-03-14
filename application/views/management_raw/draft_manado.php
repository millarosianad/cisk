<style>
    td {
        text-align: left;
        font-size: 12px;
    }

    th {
        text-align: left;
        font-size: 13px;
    }
</style>

<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>

    <div class="card-block">
        <div class="row">
            <div class="col-md-12">
                <div class="dt-responsive table-responsive">
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No Sales</th>
                                <th>ID Customer</th>
                                <th>Nama Customer</th>
                                <th>Segment</th>
                                <th>Kodeprod MPM</th>
                                <th>Nama Produk</th>
                                <th>Bruto</th>
                                <th>Netto</th>
                                <th>Year - Month</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        $no = 1;
                        foreach ($get_raw_draft->result() as $a) : ?>
                            <tr>
                                <td><?= $a->no_sales; ?></td>
                                <td><?= $a->customerid; ?></td>
                                <td><?= $a->nama_customer; ?></td>
                                <td><?= $a->segmentid; ?></td>
                                <td><?= $a->product_id_supplier; ?></td>
                                <td><?= $a->nama_invoice; ?></td>
                                <td><?= $a->rp_kotor; ?></td>
                                <td><?= $a->rp_netto; ?></td>
                                <td><?= $a->Year.$a->Month; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <hr>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Lanjut Proses
        </button>
    </div>
</div>

<?= $this->load->view('management_raw/confirm_data'); ?>

<script>
    $(document).ready(function () {
        $('#example').DataTable({
            "pageLength": 10,
            "aLengthMenu": [
                [1000, 2000, -1],
                [1000, 2000, "All"]
            ],
        });

        $(".loading").hide();
        $(".submit").hide();
        $(function () {
            $('select[name=status_closing]').on('change', function () {
                var status = $(this).children("option:selected").val();
                if (status == 'null') {
                    $(".submit").hide();
                } else {
                    $(".submit").show();
                }
            });
        });
        $(".submit").click(function () {
            $(".loading").show();
            $(".submit").hide();
        });
    });
</script>