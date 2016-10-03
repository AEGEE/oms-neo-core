<?php $nrCrt = 0; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Recruted users</title>
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
					Gender
				</th>
				<th>
					Registration email
				</th>
				<th>
					Phone
				</th>
				<th>
					Address
				</th>
				<th>
					City
				</th>
				<th>
					University
				</th>
				<th>
					Antenna
				</th>
				<th>
					Status
				</th>
			</tr>
			@foreach($users as $user)
				<tr>
					<td class="nrCrtColl">{{++$nrCrt}}</td>
					<td>{{$user->first_name." ".$user->last_name}}</td>
					<td>{{$user->date_of_birth->format('d/m/Y')}}</td>
					<td>{{$user->getGenderText()}}</td>
					<td>{{$user->email}}</td>
					<td>{{$user->phone}}</td>
					<td>{{$user->address}}</td>
					<td>{{$user->city}}</td>
					<td>{{$user->university}}</td>
					<td>{{$user->antenna_name}}</td>
					<td>{{$user->getStatus(false)}}</td>
				</tr>
			@endforeach
		</table>
	</body>
</html>