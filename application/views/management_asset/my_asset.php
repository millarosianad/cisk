<style>
    table th,
    table td {
        /* overflow: hidden !important; */
        white-space: normal !important;
    }

    th {
        font-size: 14px !important;
        text-transform: capitalize;
        color: white !important;
        background-color: darkslategray !important;
    }

    td {
        text-transform: uppercase;
        font-size: 13px;
        text-align: center;
    }
</style>

<div class="container">
    <div class="col">
        <p class="az-content-label" align="center">ASSET</p>
        <br>
        <table id="example" class="display" style="display: inline-block; overflow-y: scroll;">
            <thead>
                <tr>
                    <th class="text-center col-1" style="width: 1%;">
                        <font color="white">No
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Barang
                    </th>
                    <th class="text-center col-1">
                        <font color="white">Tanggal Terima
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $no = 1;
                    foreach ($asset->result() as $key) :?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $key->barang; ?></td>
                    <td><?= $key->tgl_penyerahan ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#example").DataTable({
            "pageLength": 100,
            "ordering": false,
            "aLengthMenu": [
                [10, 20, 50, -1],
                [10, 20, 50, "All"]
            ],
            "fixedHeader": {
                header: true,
                footer: true
            }
        });
    });
</script>