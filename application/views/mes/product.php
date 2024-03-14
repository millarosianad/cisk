
 

<div class="card">
    <div class="card-header">
        <h5><?= $title; ?></h5>
    </div>
    <div class="card-block">
        <?php echo form_open_multipart($url); ?>
        <div class="row">        
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="productid">Product ID</label>
                            <input type="text" class="form-control" id="productid" name="productid" placeholder="Enter productid">
                            <small id="emailHelp" class="form-text text-muted">tipe data dapat berubah string ataupun angka</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_product">Nama Product</label>
                            <input type="text" class="form-control" name="nama_product" placeholder="Enter nama product">
                        </div> 
                    </div>
                </div>   
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="harga">Harga</label>
                            <input type="text" class="form-control" name="harga" placeholder="Enter harga">
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input type="text" class="form-control" name="discount" placeholder="Enter discount">
                        </div> 
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="satuan1">Satuan Besar / Sedang</label>
                            <input type="text" class="form-control" name="satuan1" placeholder="Enter satuan1">
                        </div>   
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit1">Quantity (1)</label>
                            <input type="text" class="form-control" name="unit1" placeholder="Enter unit1">
                        </div>  
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="satuan2">Satuan Kecil</label>
                            <input type="text" class="form-control" name="satuan2" placeholder="Enter satuan2">
                        </div>   
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit2">Quantity (2)</label>
                            <input type="text" class="form-control" name="unit2" placeholder="Enter unit2">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        
                        <?php echo form_submit('submit', 'Simpan Product', 'class="btn btn-primary"'); ?>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            &nbsp; 
                        </div>  
                    </div>
                </div>
            </div>                                   
        </div>
    </div>

    <hr>

    <div class="card-block">
        <div class="row">
            <div class="col-md-6">
                <?php echo form_open_multipart($url_import); ?>
                
                <div class="form-group">
                    <label for="import_transaksi">Import CSV</label>
                    <input class="form-control" type="file" name="file" required/>
                </div>

                <div class="form-group">
                    <a href="<?= base_url().'mes/template_product' ?>" class="btn btn-outline-warning">download template product</a>
                    <?php echo form_submit('submit', 'Import Product With CSV', 'class="btn btn-primary"'); ?>
                    <?php echo form_close(); ?>
                </div> 

            </div>
        </div>
    </div>

    <hr>


    <div class="card-block">
    
        <div class="row">
            <div class="col-md-12">

                <table id="example" class="table table-striped table-bordered" style="display: inline-block; overflow-y: scroll">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product ID</th>
                            <th width="100%">Nama Product</th>
                            <th>Harga</th>
                            <th>Discount</th>
                            <th>Satuan 1</th>
                            <th>Unit 1</th>
                            <th>Satuan 2</th>
                            <th>Unit 2</th>
                            <th><center>#</center></th>
                        </tr>
                    </thead>
                    <tbody>     
                        <?php 
                        $no = 1;
                        foreach ($get_product->result() as $a) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>                                
                                <?php 
                                    if ($a->deleted_at) { ?>
                                        <s><?= $a->productid; ?></s>
                                    <?php }else{
                                        echo $a->productid;
                                    }
                                ?>
                            </td>
                            <td><?= $a->nama_product; ?></td>
                            <td><?= $a->harga; ?></td>
                            <td><?= $a->discount; ?></td>
                            <td><?= $a->satuan1; ?></td>
                            <td><?= $a->unit1; ?></td>
                            <td><?= $a->satuan2; ?></td>
                            <td><?= $a->unit2; ?></td>
                            <td>
                            <a href="<?= base_url().'mes/product_edit/'.$a->signature ?>" class="btn btn-outline-warning">edit</a>
                            <a href="<?= base_url().'mes/product_delete/'.$a->signature ?>" class="btn btn-outline-danger">delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>   
                    </tbody>
                </table>

            </div>
        </div>

    </div>


</div>



<script>
    $(document).ready(function () {
        $('#example').DataTable();
    });
</script>