<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Admin Page | View All Omzet</title>
	<!-- Load Jquery, Bootstrap, dan DataTables dari CDN -->
	<!-- buka url ini: http://pastebin.com/index/WeaY5Fra -->
	<!-- load Jquery dari CDN -->
	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	
	<!-- Load Datatables dan Bootstrap dari CDN -->
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">

</head>
<body>
	<!-- dalam div row harus ada col yang maksimum nilaiya 12 -->
	<div class="row">
		<div class="col-sm-0"></div>
		<div class="col-sm-10">

			<h1>Data Omzet</h1><hr />
			<a href="<?php echo base_url()."omzet/first" ?>" class="btn btn-default" role="button">2010</a>
			<a href="<?php echo base_url()."omzet/second" ?>" class="btn btn-default" role="button">2011</a>
			<a href="<?php echo base_url()."omzet/third" ?>" class="btn btn-default" role="button">2012</a>
			<a href="<?php echo base_url()."omzet/fourth" ?>" class="btn btn-default" role="button">2013</a>
			<a href="<?php echo base_url()."omzet/fifth" ?>" class="btn btn-default" role="button">2014</a>
			<a href="<?php echo base_url()."omzet/sixth" ?>" class="btn btn-default" role="button">2015</a>
			<a href="<?php echo base_url()."omzet/" ?>" class="btn btn-default" role="button">2016</a>

			<a href="<?php echo base_url()."omzet/export_omzet" ?>" class="btn btn-primary" role="button">export to excel</a>

			<hr />

			<table id="myTable" class="table table-striped table-bordered table-hover">		
			<thead>
				<tr>
					<th>NO DP</th>
					<th>NAMA DP</th>
					<th>JAN</th>
					<th>FEB</th>
					<th>MAR</th>
					<th>APR</th>
					<th>MEI</th>
					<th>JUN</th>
					<th>JUL</th>
					<th>AGS</th>
					<th>SEP</th>
					<th>OKT</th>
					<th>NOV</th>
					<th>DES</th>
					<th>Total</th>
					<th>Rata-Rata</th>
					<th>Per Tanggal</th>
				</tr>
			</thead>
			<tbody>
			    <?php foreach($omzets as $omzet) : ?>
				<tr>
					<td><?php echo $omzet->naper; ?></td>
					<td><?php echo $omzet->namacomp; ?></td>
					<td><?php echo $omzet->b1; ?></td>
					<td><?php echo $omzet->b2; ?></td>
					<td><?php echo $omzet->b3; ?></td>
					<td><?php echo $omzet->b4; ?></td>
					<td><?php echo $omzet->b5; ?></td>
					<td><?php echo $omzet->b6; ?></td>
					<td><?php echo $omzet->b7; ?></td>
					<td><?php echo $omzet->b8; ?></td>
					<td><?php echo $omzet->b9; ?></td>
					<td><?php echo $omzet->b10; ?></td>
					<td><?php echo $omzet->b11; ?></td>
					<td><?php echo $omzet->b12; ?></td>
					<td><?php echo $omzet->total; ?></td>
					<td><?php echo $omzet->rata; ?></td>
					<td><?php echo $omzet->tgl_created; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
		</div>		
		<div class="col-sm-1"></div>		
	</div>
	<script>
	$(document).ready(function(){
    	$('#myTable').DataTable();
	});
	</script>
</body>
</html>