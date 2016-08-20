<?php $nrCrt = 0; ?>
<!DOCTYPE html>
<html>
	<head>
		<title>Fees</title>
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
					Availability
				</th>
				<th>
					Availability unit
				</th>
				<th>
					Price (currency)
				</th>
				<th>
					Mandatory for all members
				</th>
			</tr>
			@foreach($fees as $fee)
				<?php
					$availabilityUnitStr = "";
		            switch ($fee->availability_unit) {
		            	case '1':
		            		$availabilityUnitStr = "Month";
		            		break;
		            	case '2':
		            		$availabilityUnitStr = "Year";
		            		break;
		            }
				?>
				<tr>
					<td class="nrCrtColl">{{++$nrCrt}}</td>
					<td>{{$fee->name}}</td>
					<td>{{$fee->availability}}</td>
					<td>{{$availabilityUnitStr}}</td>
					<td>{{$fee->price."(".$fee->currency.")"}}</td>
					<td>{{empty($fee->is_mandatory) ? "No" : "Yes"}}</td>
				</tr>
			@endforeach
		</table>
	</body>
</html>