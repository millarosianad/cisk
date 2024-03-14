<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>View All File</title>
	<link rel="stylesheet" href="">
	<!-- load JQuery, Bootstrap, dan DataTables dari CDN -->
	<!--
	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	-->
	<script type="text/javascript" language="javascript" src="<?php echo base_url().'assets/css/download/jquery-1.10.2.min.js' ?>"></script>
	
	<!-- Load dataTables dan bootstrap dari CDN -->
	<!--
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	-->
	<script type="text/javascript" language="javascript" src="<?php echo base_url().'assets/css/download/jquery.dataTables.min.js' ?>"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url().'assets/css/download/dataTables.bootstrap.js' ?>"></script>
	
	<!--
	<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css">
	-->
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/download/bootstrap.min.css' ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/download/dataTables.bootstrap.css' ?>">
	

</head>
<body>

	<div class = "row">
		<div class = "col-md-1"></div>
		<div class = "col-md-10">
			
			<h1>Data File</h1>
				<table id="myTable" class="table table-striped table-bordered table-hover">	
					<thead>
						<tr>
							<th>#</th>
							<th>Title</th>
							<th>Nama File</th>
							<th>Tanggal Upload</th>
							<th><center>
									<?php echo anchor('download/create', 'Upload Data', array('class' => 'btn btn-primary btn-xs')); ?>
								</center>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$id = 1;
							foreach ($files as $file) : ?>
						<?php 
							$convert_tanggal = str_replace('-', '', $file->tanggal_upload); 
							$nama_folder = substr($convert_tanggal, 0,8); 
						?> 
						<tr>
							<td><?php echo $id ?></td>
							<td><?php echo $file->title ?></td>
							<td><?php echo $file->nama_file ?></td>
							<td><?php echo $file->tanggal_upload ?></td>
							<td align="center">
								<?php 
									echo anchor('download/delete/' . $file->id, 'Delete',
												array
													(
														'class' => 'btn btn-danger btn-sm',
														'onclick'=>'return confirm(\'Apakah anda yakin ?\')'
													)
												); 
								?> || 
								<?php 
									echo anchor(base_url() .'assets/uploads/download/' .$nama_folder.'/'.$file->nama_file, 'Download',
												array('class'=>'btn btn-success btn-sm')); 
								?>								
							</td>
						</tr>
						<?php 
							$id++;
							endforeach; 
						?>
					</tbody>
				</table>

		</div>
		<div class = "col-md-1"></div>
	</div>

	

	<script>
		$(document).ready(function(){
	    	$('#myTable').DataTable();
		});
	</script>
</body>
</html>