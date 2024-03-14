<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="<?php echo base_url().'assets/' ?>popper/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/' ?>popper/tail.select-bootstrap4.css">
    <link rel="stylesheet" href="<?php echo base_url().'assets/' ?>popper/tail.select-funky.css">
    <link rel="stylesheet" href="<?php echo base_url().'assets/' ?>popper/tail.select-bootstrap4.less">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 




    <script src="https:/code.jquery.com/jquery-1.11.1.min.js"></script>
    <script>
    $(function () {
        $('#dinamis .add-row').click(function () {
            var template = '<tr><td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td><td><button type="button" class="btn btn-danger delete-row">-</button></td></tr>';
            $('#dinamis tbody').append(template);
        });

        $('#dinamis').on('click', '.delete-row', function () {
            $(this).parent().parent().remove();
        });
    })
    </script>



</head>
<body>
<br>
<?php echo form_open($url);?>

<h3><center><?php echo $page_title; ?></center></h3>
<hr>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            Pilih Produk Pembelian
        </div>
        <div class="col-xs-10">
            <select name="kodeprod_beli[]" multiple id = "select1">
                <?php foreach($product->result() as $row):?>
                    <option value="<?php echo $row->kodeprod;?>">
                    <?php 
                        echo $row->namaprod. ' - ' .$row->kodeprod;
                    ?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            &nbsp;
        </div>
        <div class="col-xs-10">
            <div class="move-container mt-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            Jumlah Pembelian
        </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" name = "unit_beli" placeholder="satuan terkecil" value="">
        </div>
    </div>
</div>
<br>

<hr>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            Pilih Produk Bonus
        </div>
        <div class="col-xs-10">
            <select name="kodeprod_bonus[]" multiple id = "select2">
                <?php foreach($product->result() as $row):?>
                    <option value="<?php echo $row->kodeprod;?>">
                    <?php 
                        echo $row->namaprod. ' - ' .$row->kodeprod;
                    ?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            &nbsp;
        </div>
        <div class="col-xs-10">
            <div class="move-container2 mt-2"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            Jumlah Bonus
        </div>
        <div class="col-xs-5">
            <input type="text" class="form-control" name = "unit_bonus" placeholder="satuan terkecil" value="">
        </div>
    </div>
</div>

<hr>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            Keterangan tambahan
        </div>
        <div class="col-xs-5">
            <textarea class="form-control" name = "keterangan" placeholder="Tulis nama program disini"></textarea>
        </div>
    </div>
</div>

<br>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            Kondisi tambahan
        </div>
        <div class="col-xs-5">
        <input type="checkbox" class="form-control-input" name="status_kelipatan" value="1"> berlaku kelipatan<br>
        <input type="checkbox" class="form-control-input" name="status_faktur" value="1"> harus dalam 1 faktur
        
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            &nbsp;
        </div>
        <div class="col-xs-10">
            <input type="hidden" class="form-control" name = "id" value="<?php echo $id; ?>">
            <?php echo br().form_submit('proses','Simpan','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>
        </div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            &nbsp;
        </div>
        
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-1">
        &nbsp;
        </div>
        <div class="col-xs-12">
            <table class="table table-bordered table-hover table-outlet">
                <thead>
                    <tr>
                        <th width="1%"><font size="2px">No</th>
                        <th width="20%"><font size="2px">kodeprod beli</th>
                        <th width="10%"><font size="2px">unit beli</th>
                        <th width="20%"><font size="2px">kodeprod bonus</th>
                        <th width="10%"><font size="2px">unit bonus</th>
                        <th width="30%"><font size="2px">keterangan</th>
                        <th width="1%"><font size="2px">Kelipatan</th>
                        <th width="1%"><font size="2px">Faktur</th>
                        <th width="1%"><font size="2px">edit</th>
                        <th width="1%"><font size="2px">hapus</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

    <!-- <script src="<?php echo base_url().'assets/' ?>popper/jquery-3.3.1.slim.min.js"></script> -->
    <script src="<?php echo base_url().'assets/' ?>popper/popper.min.js"></script>
    <script src="<?php echo base_url().'assets/' ?>popper/bootstrap.min.js"></script>
    <script src="<?php echo base_url().'assets/' ?>popper/tail.select-full.min.js"></script>
    
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>

    <script>
        tail.select('#select1',{
            search : true,
            // descriptions : true,
            multilimit : 15,
            hideSelected: true,
            hideDisabled: true,
            multiShowCount: false,
            multiContainer: '.move-container' 
        });
        tail.select('#select2',{
            search : true,
            // descriptions : true,
            multilimit : 15,
            hideSelected: true,
            hideDisabled: true,
            multiShowCount: false,
            multiContainer: '.move-container2' 
        });
    </script>
    <script type="text/javascript">
        $(".table-outlet").DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: {
            url: "<?php echo base_url('monitor_claim/ambil_data_program/'.$id) ?>",
            type:'POST',
            }
        });
    </script>


















<div class="row">
        <div class="col-md-12">
            <table class="table table-hover" id="dinamis">
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>Kelas</th>
                        <th>Telephone</th>
                        <th>
                            <button type="button" class="btn btn-blue add-row">+</button></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <button type="button" class="btn btn-danger delete-row">-</button></td>
                    </tr>
                    <tr>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <button type="button" class="btn btn-danger delete-row">-</button></td>

                    </tr>
                    <tr>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <button type="button" class="btn btn-danger delete-row">-</button></td>

                    </tr>
                    <tr>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <input class="form-control" type="text"></td>
                        <td>
                            <button type="button" class="btn btn-danger delete-row">-</button></td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>













</body>
</html>