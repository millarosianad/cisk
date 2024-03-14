<?php echo form_open($url); ?>
<div>
    <a href="<?php echo base_url()."transaction/konfirmasi_po"; ?>  " class="btn btn-dark btn-md" role="button"><span
            class="glyphicon glyphicon-plus" aria-hidden="true"></span> kembali</a>
    <hr>
</div>

<div class="dt-responsive table-responsive">
    <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
        <thead>
            <tr>
                <th>
                    <center><input type="button" class="btn btn-default btn-sm" id="toggle" value="click all"
                            onclick="click_all_request()">
                </th>
                <th>
                    Tanggal Terima
                </th>
                <th>
                    Kodeprod
                </th>
                <th>
                    Namaprod
                </th>
                <th>
                    No Do
                </th>
                <th>
                    Tgl Do
                </th>
                <th>
                    Qty Pemenuhan
                </th>
            </tr>
        </thead>
        <tbody>
            <?php  foreach($get_do as $a) : ?>
            <tr>
                <td>
                    <center>
                        <input type="checkbox" id="<?php echo $a->kodeprod; ?>" name="options[]"
                            class="<?php echo $a->kodeprod; ?>" value="<?php echo $a->kodeprod; ?>">
                    </center>
                </td>
                <td>
                    <?php
                                    if ($a->status_terima == 1) {
                                        echo $a->tanggal_terima;
                                    }else{
                                        echo "belum diterima";
                                    }
                                ?>
                </td>
                <td>
                    <?php echo $a->kodeprod; ?>
                </td>
                <td>
                    <font size="1px"><?php echo $a->namaprod; ?>
                </td>
                <td>
                    <?php echo $a->nodo; ?>
                </td>
                <td>
                    <?php echo $a->tgldo; ?>
                </td>
                <td>
                    <?php echo $a->qty; ?>
                </td>
            </tr>

            <?php endforeach; ?>

        </tbody>

    </table>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">Tanggal terima (*)</label>
    <div class="col-sm-9">
        <div class="col-sm-6">
            <!-- <textarea name="note" cols="30" rows="3" class = "form-control" required></textarea> -->
            <input class="form-control" type="date" name="tanggal_terima" />
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-2"></div>
    <div class="col-sm-9">
        <div class="col-sm-6">
            <?php 
                $uri_id = $this->uri->segment('4');
                $uri_supp = $this->uri->segment('3');
                // echo $uri_id;
            ?>
            <input type="hidden" value=<?php echo $uri_id; ?> name="id_po">
            <input type="hidden" value=<?php echo $uri_supp; ?> name="supp">

            <button type="submit" class="btn btn-primary" name="btn_terima" value="1"
                onclick="return confirm('Apakah yakin anda sudah terima do tsb?');">Sudah Terima</button>
            <button type="submit" class="btn btn-warning" name="btn_cancel" value="1"
                onclick="return confirm('Apakah yakin belum menerima do tsb?');">Belum Terima</button>

        </div>
    </div>
</div>