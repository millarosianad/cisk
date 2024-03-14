<style>
    #clockdiv{
	font-family: sans-serif;
	color: #fff;
	display: inline-block;
	font-weight: 100;
	text-align: center;
	font-size: 30px;
    }

    #clockdiv > div{
        padding: 10px;
        border-radius: 3px;
        background: #FFA200;
        display: inline-block;
    }

    #clockdiv div > span{
        padding: 15px;
        border-radius: 3px;
        background: #FFB64D;
        display: inline-block;
    }

    .smalltext{
        padding-top: 5px;
        font-size: 16px;
    }
</style>

<a href="<?= base_url("transaction/reset_checkout/$signature/$supp"); ?>" class="btn btn-dark btn-mat">Kembali Ke Cart</a>
<br>

<!-- tampilan countdown -->
<!-- <div align="center">
    <h4>Sisa Waktu Checkout</h4>
    <div id="clockdiv">
    <div>
        <span class="days"></span>
        <div class="smalltext">Days</div>
    </div>
    <div>
        <span class="hours"></span>
        <div class="smalltext">Hours</div>
    </div>
    <div>
        <span class="minutes"></span>
        <div class="smalltext">Minutes</div>
    </div>
    <div>
        <span class="seconds"></span>
        <div class="smalltext">Seconds</div>
    </div>
    </div>
</div> -->

<div class="card-block" id="table-checkout">
    <div class="col-sm-12">
        <div class="form-group row">
            <div class="dt-responsive table-responsive">
                <table id="multi-colum-dt" class="table table-striped table-bordered nowrap">
                    <thead>
                        <tr>
                            <th>Kode Product</th>
                            <th>Product</th>
                            <th>QTY (Karton)</th>
                            <th>Sub Berat (Kg)</th>
                            <th>Sub Volume</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($get_data_cart as $a) : ?>
                        <tr>
                            <td>
                                <font size="2px"><?= $a->kodeprod; ?>
                            </td>
                            <td>
                                <font size="2px"><?= $a->namaprod; ?>
                            </td>
                            <td>
                                <?= $a->qty; ?>
                            <td>
                                <font size="2px"><?= $a->berat*$a->qty; ?>
                            </td>
                            <td>
                                <font size="2px"><?= $a->volume*$a->qty; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total</td>
                            <td><?= $get_total_berat->total_berat; ?></td>
                            <td><?= $get_total_berat->total_volume; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <button id="checkout" onclick="Checkout()" class="btn btn-primary btn-mat">Lanjut ke Pemilihan Alamat Kirim</button>
</div>

<div id="form-checkout">
    <hr>
    <?=form_open($url); ?>
    <div class="row admin">
        <div class="col-8">
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Customer</label>
                <div class="col-sm-10">
                    <?php
                foreach($list_client as $value)
                {
                    $customer ['0']= '-- Pilih --' ;
                    $customer[$value->id]= $value->company.' ( '.$value->kode_dp.' )';
                }
                echo form_dropdown('customer', $customer,'','class="form-control"');
                ?>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group row">
                <div class="col-sm-10">
                    <?=form_submit('submit', 'Pilih', 'class="btn btn-warning" required'); ?>
                    <?=form_close(); ?>
                </div>
            </div>
        </div>
        <hr>
    </div>

    <?=form_open($url2); ?>
    <div class="col-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Pelanggan</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="<?= $client->id?>" name="userid" readonly required
                    hidden>
                <input type="text" class="form-control" value="<?= $client->company?>" name="company" readonly required>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">NPWP</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="<?= $client->npwp?>" name="npwp" readonly required>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="<?= $client->email?>" name="email" readonly required>
            </div>
        </div>
    </div>

    <div class="col-12 admin">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Tipe</label>
            <div class="col-sm-10">
                <select name="tipe" id="tipe" class="form-control">
                    <option value="A">ALOKASI</option>
                    <option value="S">SPK</option>
                </select>
            </div>
        </div>
    </div>

    <!-- tambah variabel supp -->
    <input type="hidden" name="supp" value="<?= $this->uri->segment('4'); ?>">

    <h5 class="col-2"><b>Pilih Alamat</h5>
    <br>
    <div class="col-12">
        <div class="form-group row">
            <?php
            foreach ($alamat as $a) {
            $alamat = $a->alamat;
            $kode_alamat = $a->kode_alamat;
            $company = $a->company;
            $name = $a->name;
        ?>
            <div class="col-sm-6">
                <div class="radio radiofill radio-inline">
                    <label>
                        <input type='radio' name='kode_alamat' value='<?php echo $kode_alamat; ?>' id='kode_alamat'
                            class='checked' required /> <?php echo $alamat; ?>
                    </label>
                </div>
            </div>
            <?php
            }
        ?>
        </div>
    </div>

    <div align="center">
        <?= form_submit('submit','Proses Pesanan','class="btn btn-success"');?>
        <?= form_close();?>
    </div>
</div>

<script>
    $(document).ready(function () {
        var id = <?=$this->session->userdata('id');?> ;
        if (id == 442 || id == 588 || id == 681 || id == 547 || id == 857) {
            $('.admin').show()
            $('#checkout').hide()
        } else {
            $('.admin').remove()
            $('#form-checkout').hide()
        }
    })

    function Checkout() {
        $('#table-checkout').hide()
        $('#form-checkout').show()
    }
</script>

<!-- countdown -->
<!-- <script>
    function getTimeRemaining(endtime) {
        var now = new Date().getTime();
        var t = endtime - now;
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
            return {
                'total': t,
                'days': days,
                'hours': hours,
                'minutes': minutes,
                'seconds': seconds
            };
        }

    function initializeClock(id, endtime) {
        var clock = document.getElementById(id);
        var daysSpan = clock.querySelector('.days');
        var hoursSpan = clock.querySelector('.hours');
        var minutesSpan = clock.querySelector('.minutes');
        var secondsSpan = clock.querySelector('.seconds');

    function updateClock() {
        var t = getTimeRemaining(endtime);

        // daysSpan.innerHTML = t.days;
        // hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
        minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
        secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

        if (t.total <= 0) {
            window.location.href = "<?= base_url("transaction/reset_checkout/$signature/$supp"); ?>";
        }
    }

    updateClock();
    var timeinterval = setInterval(updateClock, 1000);
    }

    var deadline = new Date("<?= $countdown;?>").getTime();
    initializeClock('clockdiv', deadline);
</script> -->