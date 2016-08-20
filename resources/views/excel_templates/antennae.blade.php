<?php $nrCrt = 0; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Antennae</title>
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
					City
				</th>
				<th>
					Country
				</th>
			</tr>
			@foreach($antennae as $antenna)
				<tr>
					<td class="nrCrtColl">{{++$nrCrt}}</td>
					<td>{{$antenna->name}}</td>
					<td>{{$antenna->city}}</td>
					<td>{{$antenna->country->name}}</td>
				</tr>
			@endforeach
		</table>
	</body>
</html>