<?php $nrCrt = 0; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Users</title>
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
					Full name
				</th>
				<th>
					Date of birth
				</th>
				<th>
					Registration email
				</th>
				<th>
					Gender
				</th>
				<th>
					Antenna
				</th>
				<th>
					Department
				</th>
				<th>
					Internal email
				</th>
				<th>
					Studies type
				</th>
				<th>
					Studies field
				</th>
			</tr>
			@foreach($users as $userX)
				<tr>
					<td class="nrCrtColl">{{++$nrCrt}}</td>
					<td>{{$userX->first_name." ".$userX->last_name}}</td>
					<td>{{$userX->date_of_birth->format('d/m/Y')}}</td>
					<td>{{$userX->contact_email}}</td>
					<td>{{$userX->gender}}</td>
					<td>{{$userX->antenna->name}}</td>
					<td>{{empty($userX->department_id) ? "-" : $userX->department->name}}</td>
					<td>{{$userX->internal_email}}</td>
					<td>{{$userX->studyField->name}}</td>
					<td>{{$userX->studyType->name}}</td>
				</tr>
			@endforeach
		</table>
	</body>
</html>