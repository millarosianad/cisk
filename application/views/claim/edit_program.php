<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="<?php echo base_url().'assets/' ?>popper/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo base_url().'assets/' ?>popper/tail.select-bootstrap4.css">
    <link rel="stylesheet" href="<?php echo base_url().'assets/' ?>popper/tail.select-invert.css">
    <link rel="stylesheet" href="<?php echo base_url().'assets/' ?>popper/tail.select-bootstrap4.less">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>"> 
</head>
<body>
<br>
<?php echo form_open('monitor_claim/proses_edit_program/'.$id);?>
<h3><center><?php echo $page_title; ?></center></h3>
<hr>
<div class="row">
    <div class="col-xs-13">
        <div class="col-xs-2">
            Pembelian Produk (1)
        </div>
        <div class="col-xs-3">
            <select name="kodeprod_beli1[]" multiple id = "select1">
                <?php foreach($product->result() as $row):?>
                    <option value="<?php echo $row->kodeprod;?>">
                    <?php 
                        echo $row->namaprod. ' - ' .$row->kodeprod;
                    ?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" name = "unit_beli1" placeholder="Unit" value="<?php echo $unit_beli1; ?>">
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" name = "value_beli1" placeholder="Value" value="<?php echo $value_beli1; ?>">
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
<br>
<div class="row">
    <div class="col-xs-13">
        <div class="col-xs-2">
            Pembelian Produk (2)
        </div>
        <div class="col-xs-3">
            <select name="kodeprod_beli2[]" multiple id = "select2">
                <?php foreach($product->result() as $row):?>
                    <option value="<?php echo $row->kodeprod;?>">
                    <?php 
                        echo $row->namaprod. ' - ' .$row->kodeprod;
                    ?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" name = "unit_beli2" placeholder="Unit" value="<?php echo $unit_beli2; ?>">
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" name = "value_beli2" placeholder="Value" value="<?php echo $value_beli2; ?>">
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
<br>
<div class="row">
    <div class="col-xs-13">
        <div class="col-xs-2">
            Pembelian Produk (3)
        </div>
        <div class="col-xs-3">
            <select name="kodeprod_beli3[]" multiple id = "select3">
                <?php foreach($product->result() as $row):?>
                    <option value="<?php echo $row->kodeprod;?>">
                    <?php 
                        echo $row->namaprod. ' - ' .$row->kodeprod;
                    ?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" name = "unit_beli3" placeholder="Unit" value="<?php echo $unit_beli3; ?>">
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" name = "value_beli3" placeholder="Value" value="<?php echo $value_beli3; ?>">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            &nbsp;
        </div>
        <div class="col-xs-10">
            <div class="move-container3 mt-2"></div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-xs-13">
        <div class="col-xs-2">
            Produk Bonus
        </div>
        <div class="col-xs-3">
            <select name="kodeprod_bonus[]" multiple id = "select4">
                <?php foreach($product->result() as $row):?>
                    <option value="<?php echo $row->kodeprod;?>">
                    <?php 
                        echo $row->namaprod. ' - ' .$row->kodeprod;
                    ?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" name = "unit_bonus" placeholder="Unit" value="<?php echo $unit_bonus; ?>">
        </div>
        <div class="col-xs-2">
            <input type="text" class="form-control" name = "value_bonus" placeholder="Value" value="<?php echo $value_bonus; ?>">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            &nbsp;
        </div>
        <div class="col-xs-10">
            <div class="move-container4 mt-2"></div>
        </div>
    </div>
</div>
<br>
<hr>
<div class="row">
    <div class="col-xs-13">
        <div class="col-xs-2">
            Nama Program
        </div>
        <div class="col-xs-5">
            <textarea class="form-control" name = "keterangan" placeholder="Tulis nama program disini"><?php echo $keterangan; ?></textarea>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-xs-13">
        <div class="col-xs-2">
            Kondisi tambahan
        </div>
        <div class="col-xs-5">
        <input type="checkbox" class="form-control-input" name="status_kelipatan" value="1" 
        <?php if($status_kelipatan == 1){ echo "checked='checked'"; }?>
        > berlaku kelipatan<br>
        <input type="checkbox" class="form-control-input" name="status_faktur" value="1"
        <?php if($status_faktur == 1){ echo "checked='checked'"; }?>
        > harus dalam 1 faktur
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-2">
            &nbsp;
        </div>
        <div class="col-xs-10">
            <input type="hidden" class="form-control" name = "id_claim" value="<?php echo $id; ?>">
            
            <?php echo br().form_submit('proses','Simpan','onclick="return ValidateCompare();" class="btn btn-primary btn-md"');?>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-13">
        <div class="col-xs-1">
        &nbsp;
        </div>
        <div class="col-xs-12">
            <table class="table table-bordered table-hover table-outlet">
                <thead>
                    <tr>
                        <th width="1%"><font size="2px">No</th>
                        <th width="20%"><font size="2px">kodeprod beli1</th>
                        <th width="10%"><font size="2px">unit beli1</th>
                        <th width="10%"><font size="2px">value beli1</th>

                        <th width="20%"><font size="2px">kodeprod beli2</th>
                        <th width="10%"><font size="2px">unit beli2</th>
                        <th width="10%"><font size="2px">value beli2</th>

                        <th width="20%"><font size="2px">kodeprod beli3</th>
                        <th width="10%"><font size="2px">unit beli3</th>
                        <th width="10%"><font size="2px">value beli3</th>

                        <th width="20%"><font size="2px">kodeprod bonus</th>
                        <th width="10%"><font size="2px">unit bonus</th>
                        <th width="10%"><font size="2px">value bonus</th>

                        <th width="30%"><font size="2px">keterangan</th>
                        <th width="1%"><font size="2px">Kelipatan</th>
                        <th width="1%"><font size="2px">Faktur</th>
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
        tail.select('#select3',{
            search : true,
            // descriptions : true,
            multilimit : 15,
            hideSelected: true,
            hideDisabled: true,
            multiShowCount: false,
            multiContainer: '.move-container3' 
        });
        tail.select('#select4',{
            search : true,
            // descriptions : true,
            multilimit : 15,
            hideSelected: true,
            hideDisabled: true,
            multiShowCount: false,
            multiContainer: '.move-container4' 
        });
    </script>
    <script type="text/javascript">
        $(".table-outlet").DataTable({
            ordering: false,
            processing: true,
            serverSide: true,
            ajax: {
            url: "<?php echo base_url('monitor_claim/ambil_data_program_detail/'.$id) ?>",
            type:'POST',
            }
        });
    </script>
</body>
</html>