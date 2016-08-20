<?php $nrCrt = 0; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Working groups</title>
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
			@foreach($workGroups as $workGroup)
				<tr>
					<td class="nrCrtColl">{{++$nrCrt}}</td>
					<td>{{$workGroup->name}}</td>
					<td>{{$workGroup->description}}</td>
				</tr>
			@endforeach
		</table>
	</body>
</html>