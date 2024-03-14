<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/bootstrap.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/datatables/css/dataTables.bootstrap.min.css' ?>">
<div class="col-sm-10">
    <!-- - Periode : <?php echo $from. " - ".$to; ?> <br>
    - Output versi : <?php echo $output_title; ?><hr> -->
    <br>
    <!-- <a href="<?php echo base_url()."inventory/omzet"; ?>"class="btn btn-dark" role="button">kembali</a> -->
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#import">Import (.csv)</button>
    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#setting_product">produk setting</button>
    <!-- <a href="<?php echo base_url()."inventory/export_ms" ?>" class="btn btn-warning" role="button">export (.csv)</a> -->
    <!-- <a href="<?php echo base_url()."inventory/konsolidasi_ms" ?>" class="btn btn-primary" role="button">Konsolidasi Data</a> -->
</div>
    </div>

    <div class="card table-card">
    <div class="card-header">
        <div class="card-block">
        <div class="dt-responsive table-responsive">
    <!-- <table id="multi-colum-dt" class="table-stock table-striped table-bordered nowrap">  -->
    <table class="table table table-striped table-bordered nowrap table-hover table-outlet">
        <thead>
            <tr>
                <!-- <th width="1%"><font size="2px">No</th> -->
                <th width="1%"><font size="2px">#</th>
                <th width="1%"><font size="2px">branch_name</th>
                <th width="4%"><font size="2px">nama_comp</th>
                <th width="4%"><font size="2px">kode</th>
                <th width="4%"><font size="2px">supp</th>
                <th width="4%"><font size="2px">namasupp</th>
                <th width="4%"><font size="2px">kodeprod</th>
                <th width="4%"><font size="2px">namaprd</th>
                <th width="4%"><font size="2px">isisatuan</th>
                <th width="4%"><font size="2px">berat</th>
                <th width="4%"><font size="2px">volume</th>
                <th width="4%"><font size="2px">harga</th>
                <th width="4%"><font size="2px">rata</th>
                <th width="4%"><font size="2px">stock_akhir_unit</th>
                <th width="4%"><font size="2px">git</th>
                <th width="4%"><font size="2px">total_stock</th>
                <th width="4%"><font size="2px">actual sales</th>
                <th width="4%"><font size="2px">estimasi_sales</th>
                <th width="4%"><font size="2px">potensi_sales</th>
                <th width="4%"><font size="2px">stock_berjalan</th>
                <th width="4%"><font size="2px">estimasi doi berjalan</th>
                <th width="4%"><font size="2px">stock_ideal</th>
                <th width="4%"><font size="2px">stock_ideal_unit</th>
                <th width="4%"><font size="2px">suggest kebutuhan stock</th>
                <th width="4%"><font size="2px">estimasi stock akhir bulan</th>
                <th width="4%"><font size="2px">estimasi doi akhir bulan</th>
                <th width="4%"><font size="2px">in karton</th>
                <th width="4%"><font size="2px">in kg</th>
                <th width="4%"><font size="2px">in volume</th>
                <th width="4%"><font size="2px">in value</th>
                <th width="4%"><font size="2px">lastupload</th>
            </tr>
        </thead>
    </table>


    <script type="text/javascript" src="<?php echo base_url('assets/jquery.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/datatables/media/js/dataTables.bootstrap.min.js') ?>"></script>
    <script type="text/javascript">
    $(".table-outlet").DataTable({
        ordering: false,
        processing: true,
        serverSide: true,
        ajax: {
        url: "<?php echo base_url('inventory/ambil_data') ?>",
        type:'POST',
        }
    });

    </script>

    <?php
    // $this->load->view('retur/modal_tambah_produk');
    // $this->load->view('retur/modal_edit_produk_pengajuan');
    // $this->load->view('master_product/modal_pricelist_by_id');
    $this->load->view('inventory/import');
    $this->load->view('inventory/setting_product');
    ?>

    </body>
</html>