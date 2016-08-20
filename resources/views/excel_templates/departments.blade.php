<?php $nrCrt = 0; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Departments</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			table > tr > td {
				border: 1px solid #000000;
			}
			table > tr > th {
				border: 1px solid #000000;
				text-align: center;
				font-weight: bold;
			}
			.nrCrtColl {
				font-weight: bold;
			}
		</style>
	</head>
	<body>
		<table>
			<tr>
				<th>
					Nr. crt.
				</th>
				<th>
					Name
				</th>
				<th>
					Description
				</th>
			</tr>
			@foreach($departments as $department)
				<tr>
					<td class="nrCrtColl">{{++$nrCrt}}</td>
					<td>{{$department->name}}</td>
					<td>{{$department->description}}</td>
				</tr>
			@endforeach
		</table>
	</body>
</html>